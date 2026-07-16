# Step 3 - Course Management (Course/Sections/Lessons/Media/Enrollment/Progress)

## Refactor & Implement Architecture

- [ ] Create Services + Repositories for:
  - [ ] Courses
  - [ ] Course Sections
  - [ ] Lessons
  - [ ] Course Media
  - [ ] Enrollments
  - [ ] Course Progress
- [ ] Create/Refactor Form Requests for all endpoints (no inline validation):
  - [ ] Courses: store/update/search/filter params
  - [ ] Sections: store/update + index filters
  - [ ] Lessons: store/update + index filters
  - [ ] Media: store/update + upload validation
  - [ ] Enrollments: enroll/unenroll
  - [ ] Progress: update/show
- [ ] Create API Resources for Courses, Course Media, Enrollment, Course Progress (and ensure consistent pagination shape).
- [ ] Implement/Update Policies + Gates and wire them via `$this->authorize()` / `Gate::authorize()` in every controller.
- [ ] Refactor Controllers to:
  - [ ] Remove inline business logic
  - [ ] Remove `$request->validate()` usage
  - [ ] Remove direct Eloquent queries
  - [ ] Use Controller -> Policy/Gate -> Service -> Repository -> Model
- [ ] Remove any temporary/minimal implementations and ensure consistent response types (API Resources).

## Data Layer / Seeds

- [ ] Update seeders to create course data graph (roles, user, category, courses, sections, lessons, media, enrollments, progress).
- [ ] Ensure migrations run cleanly.

## Feature Tests (Comprehensive)

- [ ] Courses CRUD + search + category filter + pagination
- [ ] Sections CRUD + course filter + pagination
- [ ] Lessons CRUD + pagination
- [ ] Course Media: upload/update/delete/show/list-by-course (images/videos/pdfs)
- [ ] Enrollment: enroll/unenroll/my-courses
- [ ] Course Progress: update/get/completion status
- [ ] RBAC matrix: super_admin/admin/instructor/student
- [ ] Authorization: 401 unauthorized vs 403 forbidden via middleware and policies/gates
- [ ] Validation failures (missing fields, invalid IDs, invalid uploads)
- [ ] Resources: verify every endpoint returns JSON from API Resources only

## Verification (Must All Pass)

- [ ] php artisan migrate
- [ ] php artisan route:list
- [ ] php artisan test
- [ ] php artisan optimize
