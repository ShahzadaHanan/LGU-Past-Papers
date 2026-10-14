# Graph Report - LGU-Past-Papers  (2026-09-16)

## Corpus Check
- 150 files · ~96,800 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1017 nodes · 1625 edges · 151 communities (27 shown, 35 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 67 edges (avg confidence: 0.85)
- Token cost: 0 input · 365,377 output

## Community Hubs (Navigation)
- Sub Department Module (Repository)
- Home Module
- Department Module
- Newsletter Subscriber Module
- Admin User Module
- Announcement Module
- Class Booking Module
- Testimonial Module
- Video Category Module
- Video Module
- Container Module
- Site User
- Content Block Module
- Hero Slide Module
- Subject Module
- site Module
- Sub Department Module (Admin)
- Router Module
- Site Setting Module (Repository)
- Paper Module (Admin)
- Department Module (Admin)
- livepaper Module
- User Auth Module
- Paper Module (Repository)
- Paper Submission Module (Repository)
- Site Setting Module (Admin)
- Model Module
- Session Module
- composer Module
- Response Module
- Auth Module
- Alumni Testimonial Module
- Login Module
- Paper Module (Service)
- Paginator Module
- Paper Submission Module (Service)
- Cache Module
- R E A D M E Module
- Csrf Module
- docker compose Module
- Query Builder Module
- R E A D M E Module 2
- Config Module
- R E A D M E Module 3
- Paper
- C L A U D E Module
- C L A U D E
- student 1
- R E A D M E Module 4
- R E A D M E Module 5
- R E A D M E Module 6
- R E A D M E Module 7
- campus hero
- R E A D M E
- R E A D M E 2
- R E A D M E 3
- R E A D M E 4
- R E A D M E 5
- R E A D M E 6
- R E A D M E 7
- R E A D M E 8
- R E A D M E 9

## God Nodes (most connected - your core abstractions)
1. `Database` - 72 edges
2. `Controller` - 53 edges
3. `SeoService` - 29 edges
4. `HomeController` - 27 edges
5. `SubDepartmentService` - 24 edges
6. `FileUploadService` - 23 edges
7. `AuthService` - 21 edges
8. `Session` - 18 edges
9. `VideoController` - 18 edges
10. `PaperService` - 17 edges

## Surprising Connections (you probably didn't know these)
- `"Student Resource" Positioning Rationale (No Hard Disclaimer)` --references--> `Footer Disclaimer ("Independent student resource — not an official... platform")`  [AMBIGUOUS]
  CLAUDE.md → livepaper.html
- `livepaper.html — Rendered Past Paper Detail Page` --references--> `LGU Crest (official seal)`  [EXTRACTED]
  livepaper.html → public/assets/images/brand/lgu-crest.png
- `auth.php (Auth Layout)` --references--> `LGU Crest (White/Dark-Background Variant)`  [EXTRACTED]
  app/views/layouts/auth.php → public/assets/images/brand/lgu-crest-white.jpg
- `Related Lectures Link ("/lectures?category=department-of-computer-science")` --shares_data_with--> `robots.txt Disallow Rules (/admin/, /login, /logout, /*?page=, /*?category=)`  [INFERRED]
  livepaper.html → public/robots.txt
- `Campus Hero Photo` --conceptually_related_to--> `variables.css (brand tokens)`  [INFERRED]
  public/assets/images/brand/campus-hero.jpg → public/assets/css/variables.css

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Docker Compose Services Sharing .env-Sourced DB Credentials** — docker_compose_app_service, docker_compose_mysql_service, docker_compose_phpmyadmin_service, docker_compose_env_vars [INFERRED 0.85]
- **SEO/Schema.org JSON-LD Pattern (SeoService + Breadcrumb Component)** — claude_seo_service, livepaper_breadcrumb_jsonld, livepaper_learningresource_jsonld [INFERRED 0.85]

## Communities (151 total, 35 thin omitted)

### Community 0 - "Sub Department Module (Repository)"
Cohesion: 0.05
Nodes (12): Database, AlumniTestimonial, self, self, SubDepartment, AlumniTestimonialRepository, SubDepartmentRepository, ActivityLogger (+4 more)

### Community 1 - "Home Module"
Cohesion: 0.08
Nodes (8): asset(), basePath(), env(), publicPath(), HomeController, View, self, SeoService

### Community 2 - "Department Module"
Cohesion: 0.06
Nodes (7): UserAuthController, self, Validator, Department, AlumniTestimonialService, DepartmentService, FileUploadService

### Community 3 - "Newsletter Subscriber Module"
Cohesion: 0.05
Nodes (8): NewsletterSubscriberController, NewsletterSubscriber, self, NewsletterSubscriberRepository, MailService, NewsletterSubscriberService, NewsletterSubscriber, PHPMailer\PHPMailer\PHPMailer

### Community 4 - "Admin User Module"
Cohesion: 0.06
Nodes (6): AdminUserController, CrudService, Repository, AdminRepository, DepartmentRepository, AdminUserService

### Community 5 - "Announcement Module"
Cohesion: 0.08
Nodes (5): AnnouncementController, Announcement, self, AnnouncementRepository, AnnouncementService

### Community 6 - "Class Booking Module"
Cohesion: 0.07
Nodes (5): ClassBookingController, ClassBooking, self, ClassBookingRepository, ClassBookingService

### Community 7 - "Testimonial Module"
Cohesion: 0.08
Nodes (5): TestimonialController, self, Testimonial, TestimonialRepository, TestimonialService

### Community 8 - "Video Category Module"
Cohesion: 0.09
Nodes (6): VideoCategoryController, Slug, self, VideoCategory, VideoCategoryRepository, VideoCategoryService

### Community 9 - "Video Module"
Cohesion: 0.09
Nodes (4): self, Video, VideoRepository, VideoService

### Community 10 - "Container Module"
Cohesion: 0.11
Nodes (12): App, Container, Csrf, Session, View, Dispatcher, Request, Closure (+4 more)

### Community 12 - "Content Block Module"
Cohesion: 0.09
Nodes (5): ContentBlockController, ContentBlock, self, ContentBlockRepository, ContentBlockService

### Community 13 - "Hero Slide Module"
Cohesion: 0.09
Nodes (5): HeroSlideController, HeroSlide, self, HeroSlideRepository, HeroSlideService

### Community 14 - "Subject Module"
Cohesion: 0.10
Nodes (4): self, Subject, SubjectRepository, SubjectService

### Community 15 - "site Module"
Cohesion: 0.09
Nodes (12): initAccordions(), initCountUp(), animate(), initGoogleSignIn(), relay(), renderButtons(), initLecturesFilter(), departmentIdForSlug() (+4 more)

### Community 16 - "Sub Department Module (Admin)"
Cohesion: 0.10
Nodes (3): SubDepartmentController, SubjectController, SubDepartmentService

### Community 17 - "Router Module"
Cohesion: 0.12
Nodes (3): Request, Route, Router

### Community 18 - "Site Setting Module (Repository)"
Cohesion: 0.11
Nodes (4): self, SiteSetting, SiteSettingRepository, SiteSettingService

### Community 19 - "Paper Module (Admin)"
Cohesion: 0.11
Nodes (3): PaperController, VideoController, NewsletterMailerService

### Community 21 - "livepaper Module"
Cohesion: 0.12
Nodes (16): auth.php (Auth Layout), Brand Identity (Garrison Green / Gold / Poppins / Crest Assets), "Student Resource" Positioning Rationale (No Hard Disclaimer), SeoService / Breadcrumb JSON-LD Pattern, Brand Assets Used (lgu-crest.png, campus-hero.jpg, lgu-crest-white.jpg, Poppins font), BreadcrumbList JSON-LD Block, Related Lectures Link ("/lectures?category=department-of-computer-science"), Footer Disclaimer ("Independent student resource — not an official... platform") (+8 more)

### Community 24 - "Paper Submission Module (Repository)"
Cohesion: 0.14
Nodes (3): PaperSubmission, self, PaperSubmissionRepository

### Community 28 - "composer Module"
Cohesion: 0.13
Nodes (14): autoload, files, psr-4, description, license, name, App\\, require (+6 more)

### Community 29 - "Response Module"
Cohesion: 0.19
Nodes (4): self, Response, AuthMiddleware, GuestMiddleware

### Community 37 - "R E A D M E Module"
Cohesion: 0.29
Nodes (7): Coding Standards (PSR-12), CRUD Foundation (Repository/CrudService), Department Module, File Upload Service, Query Builder, Slug System, Validator

### Community 39 - "docker compose Module"
Cohesion: 0.40
Nodes (6): Docker Compose Local Dev Setup Instructions, Hardcoded Docker Credentials Fixed (Known Issue #9), docker-compose.yml app service (PHP/Apache, :8080), DB credentials read from .env (${DB_ROOT_PASSWORD}, ${DB_DATABASE}, ${DB_USERNAME}, ${DB_PASSWORD}), docker-compose.yml mysql service (MySQL 8.4), docker-compose.yml phpmyadmin service (:8081)

### Community 41 - "R E A D M E Module 2"
Cohesion: 0.50
Nodes (4): Authentication System, CSRF Protection, Middleware (Auth/Guest/Role), Security Measures

### Community 43 - "R E A D M E Module 3"
Cohesion: 0.67
Nodes (3): Custom MVC Architecture (No Framework), Layered Architecture (Controller-Service-Repository-Database-MySQL), LGU Past Papers & Online Lectures Platform

## Ambiguous Edges - Review These
- `"Student Resource" Positioning Rationale (No Hard Disclaimer)` → `Footer Disclaimer ("Independent student resource — not an official... platform")`  [AMBIGUOUS]
  CLAUDE.md · relation: references
- `LGU Crest (official seal)` → `Favicon (LGU Crest Seal)`  [AMBIGUOUS]
  public/assets/images/brand/lgu-crest.png · relation: conceptually_related_to

## Knowledge Gaps
- **44 isolated node(s):** `Activity Logger`, `Dependency Injection Container`, `Mail Service (PHPMailer/SMTP)`, `Pagination`, `Request` (+39 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 518 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **35 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **What is the exact relationship between `"Student Resource" Positioning Rationale (No Hard Disclaimer)` and `Footer Disclaimer ("Independent student resource — not an official... platform")`?**
  _Edge tagged AMBIGUOUS (relation: references) - confidence is low._
- **What is the exact relationship between `LGU Crest (official seal)` and `Favicon (LGU Crest Seal)`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **Why does `Database` connect `Sub Department Module (Repository)` to `Home Module`, `Newsletter Subscriber Module`, `Admin User Module`, `Announcement Module`, `Class Booking Module`, `Testimonial Module`, `Video Category Module`, `Video Module`, `Container Module`, `Site User`, `Content Block Module`, `Hero Slide Module`, `Subject Module`, `Site Setting Module (Repository)`, `Paper Module (Admin)`, `Paper Module (Repository)`, `Paper Submission Module (Repository)`, `Model Module`, `Login Module`?**
  _High betweenness centrality (0.299) - this node is a cross-community bridge._
- **Why does `FileUploadService` connect `Department Module` to `Home Module`, `Paper Module (Service)`, `Testimonial Module`, `Container Module`, `Site User`, `Hero Slide Module`, `Department Module (Admin)`, `Paper Module (Repository)`?**
  _High betweenness centrality (0.055) - this node is a cross-community bridge._
- **Why does `Controller` connect `Site User` to `Login Module`, `Home Module`, `Department Module`, `Newsletter Subscriber Module`, `Admin User Module`, `Announcement Module`, `Class Booking Module`, `Testimonial Module`, `Video Category Module`, `Sub Department Module (Repository)`, `Container Module`, `Content Block Module`, `Hero Slide Module`, `Sub Department Module (Admin)`, `Paper Module (Admin)`, `Department Module (Admin)`, `Site Setting Module (Admin)`, `Alumni Testimonial Module`?**
  _High betweenness centrality (0.047) - this node is a cross-community bridge._
- **Are the 4 inferred relationships involving `Database` (e.g. with `.registerCoreServices()` and `.announcements()`) actually correct?**
  _`Database` has 4 INFERRED edges - model-reasoned connections that need verification._
- **Are the 17 inferred relationships involving `SeoService` (e.g. with `.aboutLgu()` and `.aboutUs()`) actually correct?**
  _`SeoService` has 17 INFERRED edges - model-reasoned connections that need verification._