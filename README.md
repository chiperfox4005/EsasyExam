# EsasyExam

EsasyExam is a web-based examination and digital learning platform designed to simplify academic assessment, question management, and educational workflows through a modern and scalable architecture.

The system combines examination management, centralized question banks, AI-assisted content generation, and role-based learning experiences into a unified educational environment.

---

## Application Preview

The following screenshot represents the current development interface of EsasyExam.

![EsasyExam Preview](https://github.com/chiperfox4005/EsasyExam/blob/main/screencapture-127-0-0-1-8000-2026-06-16-23_01_04.png?raw=true)

---

## Overview

EsasyExam provides an integrated environment where institutions can manage:

* Subjects and curriculum
* Question banks
* Digital examinations
* Learning activities
* Student progress
* AI-assisted educational content

---

## Main Features

### Authentication & Authorization

Role-based access control:

* Administrator
* Teacher
* Student

---

### Administrator Features

* Manage classes
* Manage teachers
* Manage students
* Manage subjects
* Monitor examinations

---

### Teacher Features

* Create question banks
* Import Excel questions
* Generate questions using AI
* Create examinations
* Publish assessments
* Manage images in questions

---

### Student Features

* Access learning modules
* Participate in examinations
* View examination results
* Monitor learning progress

---

### Question Bank

Supported capabilities:

* Multiple Choice Questions
* Essay Questions
* Image Support
* Question Difficulty Levels
* Draft & Publish Workflow
* Search & Filter
* Import from Excel
* AI Generated Questions

---

### Examination System

* Examination creation
* Question distribution
* Answer submission
* Result evaluation
* Performance tracking

---

## Technology Stack

| Layer              | Technology     |
| ------------------ | -------------- |
| Backend            | Laravel 13     |
| Language           | PHP 8.3        |
| Frontend           | Blade          |
| Styling            | Tailwind CSS   |
| Interactivity      | Alpine.js      |
| Icons              | Font Awesome   |
| Database           | MySQL          |
| Asset Build        | Laravel Vite   |
| Excel Processing   | Laravel Excel  |
| Spreadsheet Engine | PhpSpreadsheet |
| Version Control    | Git            |
| Repository         | GitHub         |

---

## Project Structure

```plaintext
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
```

---

## Installation

Clone repository:

```bash
git clone https://github.com/chiperfox4005/EsasyExam.git
```

Move into project:

```bash
cd EsasyExam
```

Install dependencies:

```bash
composer install
npm install
```

Create environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Configure database:

```env
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Run migration:

```bash
php artisan migrate
```

Build assets:

```bash
npm run build
```

Run application:

```bash
php artisan serve
```

---

## Development Commands

Start frontend:

```bash
npm run dev
```

Clear cache:

```bash
php artisan optimize:clear
```

Run migration:

```bash
php artisan migrate
```

---

## Repository

Repository URL:

https://github.com/chiperfox4005/EsasyExam

---

## Development Roadmap

Future improvements:

* Learning analytics
* AI recommendation system
* Gamification
* Badge system
* Leaderboards
* Real-time monitoring
* Mobile optimization

---

## License

Developed for educational and research purposes.

© FikryTech — All Rights Reserved.
