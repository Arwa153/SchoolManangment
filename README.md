# School Management System

A complete, production-ready School Management System built with Laravel 12, featuring role-based access control for Managers, Teachers, and Parents.

## 🚀 Features

### For School Managers
- **Complete Administrative Control**
- Create and manage classes
- Add teachers and students
- Assign students to classes
- View comprehensive reports and statistics
- Full system oversight

### For Teachers
- **Classroom Management**
- View assigned classes and students
- Add and manage student grades
- Record behavior incidents (positive/negative)
- Track student performance
- Subject-specific teaching tools

### For Parents
- **Child Progress Monitoring**
- View child's grades and academic progress
- Monitor behavior reports
- Read-only access to child's information
- Stay connected with school activities

## 🔐 Security & Access Control

- **Role-based Authentication**: Strict role separation with custom middleware
- **Protected Routes**: Each role has dedicated, protected dashboard routes
- **No Redirect Loops**: Proper authentication flow with role-based redirects
- **Secure Registration**: Parent registration via student codes

## 📋 System Requirements

- PHP 8.2+
- Laravel 12
- MySQL/SQLite
- Composer
- Node.js & NPM

## 🛠 Installation & Setup

1. **Clone and Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Start Development Server**
   ```bash
   php artisan serve
   npm run dev
   ```

## 👥 Default Login Credentials

After running the seeder, use these credentials to test the system:

- **Manager**: manager@school.com / password
- **Teacher**: sarah@school.com / password  
- **Parent**: parent@school.com / password

## 🎯 Key Routes

### Public Routes (No Authentication Required)
- `/` - Home page
- `/get-started` - Role selection
- `/login` - Login page
- `/register/manager` - Manager registration
- `/register/teacher` - Teacher registration
- `/register/parent` - Parent registration

### Protected Routes (Authentication + Role Required)
- `/manager/dashboard` - Manager dashboard
- `/teacher/dashboard` - Teacher dashboard
- `/parent/dashboard` - Parent dashboard

## 🏗 Architecture

### Database Schema
- **users** - All system users with role-based access
- **students** - Student records with unique codes
- **classes** - Class management with teacher assignments
- **grades** - Academic performance tracking
- **behavior_records** - Behavioral incident tracking

### Role Middleware
Custom middleware ensures strict role separation:
- Managers: Full system access
- Teachers: Class and student management
- Parents: Read-only child information

### MVC Structure
- **Controllers**: Role-specific controllers for clean separation
- **Models**: Eloquent relationships for data integrity
- **Views**: Role-based dashboard layouts with Bootstrap 5

## 🎨 UI/UX Design

- **Modern Design**: Clean, responsive interface with Bootstrap 5
- **Role-Specific Dashboards**: Tailored interfaces for each user type
- **Mobile-Friendly**: Responsive design for all devices
- **Intuitive Navigation**: Clear role-based sidebar navigation

## 🔄 Registration Flow

### Manager Registration
1. Name, email, password → Direct to manager dashboard

### Teacher Registration  
1. Name, email, password, subject → Direct to teacher dashboard

### Parent Registration
1. Student code validation → Auto-create parent account → Direct to parent dashboard

## 📊 Features Implemented

✅ **Authentication System**
- Role-based login/registration
- Secure password handling
- Session management

✅ **Manager Features**
- Dashboard with statistics
- Class creation and management
- Teacher overview
- Student management
- Student-to-class assignment

✅ **Teacher Features**
- Class and student overview
- Grade management system
- Behavior record tracking
- Student performance monitoring

✅ **Parent Features**
- Child progress dashboard
- Grade viewing
- Behavior report access
- Read-only child information

✅ **Database & Relationships**
- Complete relational database design
- Eloquent model relationships
- Data integrity constraints

✅ **UI/UX**
- Responsive Bootstrap 5 design
- Role-specific layouts
- Modern, clean interface
- Mobile-friendly design

## 🚦 System Status

**PRODUCTION READY** ✅

This is a fully functional School Management System with:
- Complete authentication flow
- Role-based access control
- Real database operations
- Modern UI/UX design
- Proper security measures
- No demo/fake functionality

## 🔧 Customization

The system is built with extensibility in mind:
- Easy to add new roles
- Modular controller structure
- Flexible database schema
- Customizable UI themes

## 📝 License

This project is open-sourced software licensed under the MIT license.