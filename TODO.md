# TODO - Course Management Module (Step 3)

- [ ] Create course domain database migrations (categories, courses, instructors profile, enrollments, progress, content, lessons, sections, media)
- [ ] Create Eloquent models with relationships
- [ ] Create policies/gates for RBAC-guarded actions (course CRUD, enrollment, progress)
- [ ] Create services + repositories
- [ ] Create form requests for validation
- [ ] Create API resources (CategoryResource, CourseResource, LessonResource, EnrollmentResource, ProgressResource, MediaResource, etc.)
- [ ] Implement controllers for: Categories, Courses, Instructors, Enrollment, Progress, Course Content, Search, Filters
- [ ] Wire versioned API routes under `/api/v1` with protected route groups
- [ ] Add seeders for base roles (if needed) + course seed data
- [ ] Update `database/seeders/DatabaseSeeder.php`
- [ ] Verify: `php artisan route:list`, `php artisan test`, `php artisan optimize`
- [ ] Fix all errors until verification is clean
