# Graph Report - LGU-Past-Papers  (2026-09-10)

## Corpus Check
- Corpus is ~27,427 words - fits in a single context window. You may not need a graph.

## Summary
- 811 nodes · 1226 edges · 138 communities (21 shown, 18 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 39 edges (avg confidence: 0.85)
- Token cost: 65,000 input · 4,000 output

## Community Hubs (Navigation)
- Model Base Class
- Bootstrap & Global Helpers
- Paper Admin Controller
- Admin User Controller
- Paper Submission Controller
- Class Booking Controller
- Newsletter Subscriber Controller
- Validator
- Site Setting Controller
- Video Controller
- Config & Database Core
- Content Block Controller
- Hero Slide Controller
- Sub-Department Controller
- Video Category Controller
- Docker Compose Stack
- Admin Dashboard & Login
- Router Dispatcher
- Public Auth Controller
- Session Management
- Alumni Testimonial Controller
- Public Home Controller
- Composer Config
- Department Controller
- Paginator
- Security Helpers
- CSRF & Form Helpers
- README Architecture Concepts
- Query Builder
- Project Identity & Architecture
- R E A D M E
- R E A D M E
- R E A D M E
- R E A D M E
- R E A D M E
- R E A D M E
- R E A D M E
- R E A D M E
- R E A D M E

## God Nodes (most connected - your core abstractions)
1. `Request` - 59 edges
2. `Response` - 59 edges
3. `Session` - 55 edges
4. `Database` - 53 edges
5. `Csrf` - 42 edges
6. `Controller` - 40 edges
7. `AuthService` - 34 edges
8. `HomeController` - 20 edges
9. `DepartmentService` - 19 edges
10. `ClassBookingController` - 15 edges

## Surprising Connections (you probably didn't know these)
- `mysql service (lgu_mysql_v2)` --shares_data_with--> `Database Migrations Runner (database/migrate.php)`  [INFERRED]
  docker-compose.yml → README.md
- `Disallow: /login` --conceptually_related_to--> `Authentication System`  [INFERRED]
  public/robots.txt → README.md
- `Disallow: /logout` --conceptually_related_to--> `Authentication System`  [INFERRED]
  public/robots.txt → README.md
- `Disallow: /admin/` --conceptually_related_to--> `Middleware (Auth/Guest/Role)`  [INFERRED]
  public/robots.txt → README.md
- `Disallow: /admin/` --conceptually_related_to--> `Admin Layout`  [INFERRED]
  public/robots.txt → README.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Local Dev Stack Communicating over lgu-network** — docker_compose_app_service, docker_compose_mysql_service, docker_compose_phpmyadmin_service [EXTRACTED 1.00]
- **Protected Admin Routes (crawler-blocked + auth-guarded)** — public_robots_admin_disallow, readme_admin_layout, readme_middleware [INFERRED 0.75]
- **Database Initialization & Migration Flow** — docker_compose_mysql_service, readme_database_migrations, readme_database [INFERRED 0.85]

## Communities (138 total, 18 thin omitted)

### Community 0 - "Model Base Class"
Cohesion: 0.06
Nodes (8): Model, self, Response, AuthMiddleware, GuestMiddleware, RoleMiddleware, Admin, AuthService

### Community 1 - "Bootstrap & Global Helpers"
Cohesion: 0.06
Nodes (13): basePath(), publicPath(), App, Container, CacheService, MailService, self, SeoService (+5 more)

### Community 2 - "Paper Admin Controller"
Cohesion: 0.07
Nodes (6): PaperController, Slug, Paper, self, PaperRepository, PaperService

### Community 3 - "Admin User Controller"
Cohesion: 0.07
Nodes (5): AdminUserController, CrudService, Repository, AdminRepository, AdminUserService

### Community 4 - "Paper Submission Controller"
Cohesion: 0.07
Nodes (5): PaperSubmissionController, PaperSubmission, self, PaperSubmissionRepository, PaperSubmissionService

### Community 5 - "Class Booking Controller"
Cohesion: 0.07
Nodes (5): ClassBookingController, ClassBooking, self, ClassBookingRepository, ClassBookingService

### Community 6 - "Newsletter Subscriber Controller"
Cohesion: 0.07
Nodes (5): NewsletterSubscriberController, NewsletterSubscriber, self, NewsletterSubscriberRepository, NewsletterSubscriberService

### Community 7 - "Validator"
Cohesion: 0.09
Nodes (6): self, Validator, Department, DepartmentRepository, DepartmentService, FileUploadService

### Community 8 - "Site Setting Controller"
Cohesion: 0.08
Nodes (5): SiteSettingController, self, SiteSetting, SiteSettingRepository, SiteSettingService

### Community 9 - "Video Controller"
Cohesion: 0.08
Nodes (5): VideoController, self, Video, VideoRepository, VideoService

### Community 10 - "Config & Database Core"
Cohesion: 0.09
Nodes (8): Config, Database, AlumniTestimonial, self, AlumniTestimonialRepository, ActivityLogger, PDO, PDOStatement

### Community 11 - "Content Block Controller"
Cohesion: 0.09
Nodes (5): ContentBlockController, ContentBlock, self, ContentBlockRepository, ContentBlockService

### Community 12 - "Hero Slide Controller"
Cohesion: 0.09
Nodes (5): HeroSlideController, HeroSlide, self, HeroSlideRepository, HeroSlideService

### Community 13 - "Sub-Department Controller"
Cohesion: 0.09
Nodes (5): SubDepartmentController, self, SubDepartment, SubDepartmentRepository, SubDepartmentService

### Community 14 - "Video Category Controller"
Cohesion: 0.09
Nodes (5): VideoCategoryController, self, VideoCategory, VideoCategoryRepository, VideoCategoryService

### Community 15 - "Docker Compose Stack"
Cohesion: 0.12
Nodes (21): app service (lgu_app_v2), lgu-network (docker network), mysql_data volume, mysql service (lgu_mysql_v2), docker/php/Dockerfile (build context), phpmyadmin service (lgu_phpmyadmin_v2), Disallow: /admin/, Disallow: /login (+13 more)

### Community 16 - "Admin Dashboard & Login"
Cohesion: 0.16
Nodes (4): DashboardController, LoginController, Controller, View

### Community 17 - "Router Dispatcher"
Cohesion: 0.16
Nodes (3): Dispatcher, Route, Router

### Community 22 - "Composer Config"
Cohesion: 0.13
Nodes (14): autoload, files, psr-4, description, license, name, App\\, require (+6 more)

### Community 27 - "README Architecture Concepts"
Cohesion: 0.29
Nodes (7): Coding Standards (PSR-12), CRUD Foundation (Repository/CrudService), Department Module, File Upload Service, Query Builder, Slug System, Validator

### Community 29 - "Project Identity & Architecture"
Cohesion: 0.67
Nodes (3): Custom MVC Architecture (No Framework), Layered Architecture (Controller-Service-Repository-Database-MySQL), LGU Past Papers & Online Lectures Platform

## Knowledge Gaps
- **32 isolated node(s):** `name`, `description`, `type`, `license`, `php` (+27 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 448 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **18 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Database` connect `Config & Database Core` to `Model Base Class`, `Bootstrap & Global Helpers`, `Paper Admin Controller`, `Admin User Controller`, `Paper Submission Controller`, `Class Booking Controller`, `Newsletter Subscriber Controller`, `Site Setting Controller`, `Video Controller`, `Content Block Controller`, `Hero Slide Controller`, `Sub-Department Controller`, `Video Category Controller`, `Admin Dashboard & Login`, `Public Home Controller`?**
  _High betweenness centrality (0.245) - this node is a cross-community bridge._
- **Why does `Request` connect `Public Auth Controller` to `Paper Admin Controller`, `Admin User Controller`, `Paper Submission Controller`, `Class Booking Controller`, `Newsletter Subscriber Controller`, `Site Setting Controller`, `Video Controller`, `Content Block Controller`, `Hero Slide Controller`, `Sub-Department Controller`, `Video Category Controller`, `Admin Dashboard & Login`, `Router Dispatcher`, `Alumni Testimonial Controller`, `Public Home Controller`, `Department Controller`?**
  _High betweenness centrality (0.068) - this node is a cross-community bridge._
- **Why does `Response` connect `Model Base Class` to `Paper Admin Controller`, `Admin User Controller`, `Paper Submission Controller`, `Class Booking Controller`, `Newsletter Subscriber Controller`, `Site Setting Controller`, `Video Controller`, `Content Block Controller`, `Hero Slide Controller`, `Sub-Department Controller`, `Video Category Controller`, `Admin Dashboard & Login`, `Public Auth Controller`, `Alumni Testimonial Controller`, `Public Home Controller`, `Department Controller`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **What connects `name`, `description`, `type` to the rest of the system?**
  _32 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Model Base Class` be split into smaller, more focused modules?**
  _Cohesion score 0.06294326241134751 - nodes in this community are weakly interconnected._
- **Should `Bootstrap & Global Helpers` be split into smaller, more focused modules?**
  _Cohesion score 0.06060606060606061 - nodes in this community are weakly interconnected._
- **Should `Paper Admin Controller` be split into smaller, more focused modules?**
  _Cohesion score 0.06882591093117409 - nodes in this community are weakly interconnected._