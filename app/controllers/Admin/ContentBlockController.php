<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\ContentBlockService;

class ContentBlockController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private ContentBlockService $service
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/content_blocks/index', [
            'title' => 'Content Blocks',
            'blocks' => $this->service->getAllContentBlocks(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/content_blocks/create', ['title' => 'Add Content Block'], 'admin');
    }

    public function store(): void
    {
        try {
            $this->service->createContentBlock($this->request->all());
            $this->session->flash('success', 'Content block created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/content-blocks');
    }

    public function edit(int $id): void
    {
        $block = $this->service->getContentBlockById($id);
        if (!$block) {
            $this->session->flash('error', 'Content block not found.');
            $this->response->redirect('/admin/content-blocks');
            return;
        }
        $this->render('admin/content_blocks/edit', ['title' => 'Edit Content Block', 'block' => $block], 'admin');
    }

    public function update(int $id): void
    {
        try {
            $this->service->updateContentBlock($id, $this->request->all());
            $this->session->flash('success', 'Content block updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/content-blocks');
    }

    public function delete(int $id): void
    {
        $this->service->deleteContentBlock($id);
        $this->session->flash('success', 'Content block deleted.');
        $this->response->redirect('/admin/content-blocks');
    }
}
