<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Request;
use App\Core\Response;
use App\Core\Csrf;


class HomeController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private Request $request,
        private Response $response
    ) {
        parent::__construct($view, $session, $csrf);
    }

    private function getDb(): \App\Core\Database
    {
        return new \App\Core\Database();
    }

    public function index(): void
    {
        $db = $this->getDb();
        
        // Fetch hero slides
        $slides = $db->fetchAll("SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY display_order ASC");
        
        // Fetch home content blocks
        $blocks = $db->fetchAll("SELECT * FROM content_blocks WHERE page_key = 'home' AND is_active = 1 ORDER BY display_order ASC");
        
        // Fetch active departments
        $departments = $db->fetchAll("SELECT * FROM departments WHERE is_active = 1 ORDER BY display_order ASC");

        $this->render(
            'home/index',
            [
                'title' => 'LGU Past Papers & Learning Portal',
                'slides' => $slides,
                'blocks' => $blocks,
                'departments' => $departments,
                'session' => $this->session
            ]
        );
    }

    public function departments(): void
    {
        $db = $this->getDb();
        $departments = $db->fetchAll("SELECT * FROM departments WHERE is_active = 1 ORDER BY display_order ASC");

        $this->render(
            'pages/departments',
            [
                'title' => 'All Academic Departments | LGU',
                'departments' => $departments
            ]
        );
    }

    public function department(string $slug): void
    {
        $db = $this->getDb();
        $department = $db->fetch("SELECT * FROM departments WHERE slug = ? AND is_active = 1", [$slug]);
        
        if (!$department) {
            $this->response->redirect('/');
            return;
        }

        // Fetch sub departments (degrees)
        $degrees = $db->fetchAll("SELECT * FROM sub_departments WHERE department_id = ? AND is_active = 1 ORDER BY display_order ASC", [$department['id']]);

        // Fetch department opportunities / career content blocks
        $blocks = $db->fetchAll("SELECT * FROM content_blocks WHERE page_key = ? AND is_active = 1 ORDER BY display_order ASC", ["dept-{$department['id']}"]);

        $this->render(
            'pages/department',
            [
                'title' => htmlspecialchars($department['meta_title'] ?? ($department['name'] . ' | LGU')),
                'department' => $department,
                'degrees' => $degrees,
                'blocks' => $blocks
            ]
        );
    }

    public function degree(string $deptSlug, string $subSlug): void
    {
        $db = $this->getDb();
        $department = $db->fetch("SELECT * FROM departments WHERE slug = ? AND is_active = 1", [$deptSlug]);
        $degree = $db->fetch("SELECT * FROM sub_departments WHERE slug = ? AND is_active = 1", [$subSlug]);

        if (!$department || !$degree) {
            $this->response->redirect('/');
            return;
        }

        // Fetch papers grouped by exam types
        $papers = $db->fetchAll("SELECT * FROM papers WHERE sub_department_id = ? ORDER BY session DESC, subject_name ASC", [$degree['id']]);
        
        $groupedPapers = [
            'mids' => [],
            'finals' => [],
            'summer_mids' => [],
            'summer_finals' => []
        ];

        foreach ($papers as $paper) {
            $groupedPapers[$paper['exam_type']][] = $paper;
        }

        $this->render(
            'pages/degree',
            [
                'title' => htmlspecialchars($degree['meta_title'] ?? ($degree['name'] . ' - Past Papers | LGU')),
                'department' => $department,
                'degree' => $degree,
                'groupedPapers' => $groupedPapers
            ]
        );
    }

    public function paper(string $slug): void
    {
        $db = $this->getDb();
        $paper = $db->fetch("SELECT * FROM papers WHERE slug = ?", [$slug]);

        if (!$paper) {
            $this->response->redirect('/');
            return;
        }

        // Increment views
        $db->execute("UPDATE papers SET views_count = views_count + 1 WHERE id = ?", [$paper['id']]);

        // Get degree & department for breadcrumbs
        $degree = $db->fetch("SELECT * FROM sub_departments WHERE id = ?", [$paper['sub_department_id']]);
        $department = $db->fetch("SELECT * FROM departments WHERE id = ?", [$degree['department_id']]);

        // Fetch related papers in the same degree and exam type
        $related = $db->fetchAll("
            SELECT * FROM papers 
            WHERE sub_department_id = ? AND exam_type = ? AND id != ? 
            LIMIT 4
        ", [$paper['sub_department_id'], $paper['exam_type'], $paper['id']]);

        $this->render(
            'pages/paper',
            [
                'title' => htmlspecialchars($paper['meta_title'] ?? ($paper['subject_name'] . ' ' . $paper['session'] . ' ' . strtoupper($paper['exam_type']) . ' | LGU')),
                'paper' => $paper,
                'degree' => $degree,
                'department' => $department,
                'related' => $related
            ]
        );
    }

    public function lectures(): void
    {
        $db = $this->getDb();
        
        $categories = $db->fetchAll("SELECT * FROM video_categories WHERE is_active = 1 ORDER BY display_order ASC");
        
        $selectedCat = $this->request->input('category', '');
        $page = max(1, (int)$this->request->input('page', 1));
        $perPage = 8;
        $offset = ($page - 1) * $perPage;

        $query = "SELECT v.*, vc.name as category_name FROM videos v 
                  JOIN video_categories vc ON v.category_id = vc.id 
                  WHERE v.is_active = 1";
        $params = [];

        if (!empty($selectedCat)) {
            $query .= " AND vc.slug = ?";
            $params[] = $selectedCat;
        }

        $query .= " ORDER BY v.display_order ASC, v.id DESC LIMIT $perPage OFFSET $offset";
        $videos = $db->fetchAll($query, $params);

        // Count total
        $countQuery = "SELECT COUNT(*) as total FROM videos v JOIN video_categories vc ON v.category_id = vc.id WHERE v.is_active = 1";
        if (!empty($selectedCat)) {
            $countQuery .= " AND vc.slug = ?";
            $total = (int)$db->fetch($countQuery, [$selectedCat])['total'];
        } else {
            $total = (int)$db->fetch($countQuery)['total'];
        }

        $this->render(
            'pages/lectures',
            [
                'title' => 'Video Lectures & Online Classes | LGU',
                'categories' => $categories,
                'selectedCategory' => $selectedCat,
                'videos' => $videos,
                'page' => $page,
                'totalPages' => ceil($total / $perPage)
            ]
        );
    }

    public function bookClass(): void
    {
        if ($this->request->isPost()) {
            $db = $this->getDb();
            try {
                $name = trim($this->request->input('name', ''));
                $email = trim($this->request->input('email', ''));
                $whatsapp = trim($this->request->input('whatsapp', ''));
                $subject = trim($this->request->input('subject', ''));
                $topic = trim($this->request->input('topic', ''));
                $feeType = trim($this->request->input('fee_type', ''));
                $description = trim($this->request->input('description', ''));

                if (empty($name) || empty($email) || empty($whatsapp) || empty($subject)) {
                    throw new \Exception('Please fill in all required fields.');
                }

                $db->execute("
                    INSERT INTO class_bookings (name, email, whatsapp, subject, topic, fee_type, description, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'new')
                ", [$name, $email, $whatsapp, $subject, $topic, $feeType, $description]);

                // Send email to admin using MailService if configured
                // For WhatsApp, we will return a wa.me redirect link if requested by user,
                // or just flash a success message and direct to WhatsApp chat.
                $this->session->flash('success', 'Your booking request has been submitted successfully! We will contact you soon.');
            } catch (\Exception $e) {
                $this->session->flash('error', $e->getMessage());
            }
        }
        $this->response->redirect('/lectures');
    }

    public function subscribeNewsletter(): void
    {
        if ($this->request->isPost()) {
            $db = $this->getDb();
            try {
                $email = trim($this->request->input('email', ''));
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception('Please enter a valid email address.');
                }

                $existing = $db->fetch("SELECT * FROM newsletter_subscribers WHERE email = ?", [$email]);
                if ($existing) {
                    $this->session->flash('success', 'You are already subscribed to our newsletter!');
                } else {
                    $token = bin2hex(random_bytes(16));
                    $db->execute("
                        INSERT INTO newsletter_subscribers (email, is_verified, unsubscribe_token)
                        VALUES (?, 1, ?)
                    ", [$email, $token]);
                    $this->session->flash('success', 'Thank you for subscribing to our newsletter alerts!');
                }
            } catch (\Exception $e) {
                $this->session->flash('error', $e->getMessage());
            }
        }
        $this->response->redirect('/');
    }

    public function submitPaper(): void
    {
        if ($this->request->isPost()) {
            $db = $this->getDb();
            try {
                $name = trim($this->request->input('name', ''));
                $email = trim($this->request->input('email', ''));
                $whatsapp = trim($this->request->input('whatsapp', ''));
                $subject = trim($this->request->input('subject', ''));
                $description = trim($this->request->input('description', ''));

                if (empty($name) || empty($email) || empty($whatsapp) || empty($subject) || empty($_FILES['paper_image']['name'])) {
                    throw new \Exception('Please fill in all required fields and upload the paper image.');
                }

                // File upload using raw helper or file service
                $uploadsService = new \App\Services\FileUploadService();
                $imagePath = $uploadsService->upload($_FILES['paper_image'], 'submissions');

                $db->execute("
                    INSERT INTO paper_submissions (name, email, whatsapp, subject, description, image_path, status)
                    VALUES (?, ?, ?, ?, ?, ?, 'pending')
                ", [$name, $email, $whatsapp, $subject, $description, $imagePath]);

                $this->session->flash('success', 'Your paper has been submitted successfully for admin moderation!');
            } catch (\Exception $e) {
                $this->session->flash('error', $e->getMessage());
            }
        }
        $this->response->redirect('/contact-us');
    }

    public function aboutLgu(): void
    {
        $db = $this->getDb();
        $blocks = $db->fetchAll("SELECT * FROM content_blocks WHERE page_key = 'about-lgu' AND is_active = 1 ORDER BY display_order ASC");
        
        $this->render(
            'pages/about_lgu',
            [
                'title' => 'About Lahore Garrison University',
                'blocks' => $blocks
            ]
        );
    }

    public function aboutUs(): void
    {
        $db = $this->getDb();
        $blocks = $db->fetchAll("SELECT * FROM content_blocks WHERE page_key = 'about-us' AND is_active = 1 ORDER BY display_order ASC");
        
        $this->render(
            'pages/about_us',
            [
                'title' => 'About Us | LGU Past Papers',
                'blocks' => $blocks
            ]
        );
    }

    public function alumni(): void
    {
        $db = $this->getDb();
        $alumni = $db->fetchAll("SELECT * FROM alumni WHERE is_active = 1 ORDER BY display_order ASC");
        
        $this->render(
            'pages/alumni',
            [
                'title' => 'Alumni Testimonials | LGU',
                'alumni' => $alumni
            ]
        );
    }

    public function contactUs(): void
    {
        $this->render(
            'pages/contact_us',
            [
                'title' => 'Contact Us & Submit a Paper | LGU'
            ]
        );
    }
}