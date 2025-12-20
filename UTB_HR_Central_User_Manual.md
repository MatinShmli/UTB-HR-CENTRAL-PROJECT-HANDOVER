# UTB HR Central - User Manual

**Version:** 1.0  
**Date:** November 2025  
**Organization:** Universiti Teknologi Brunei (UTB)

---

## Table of Contents

1. [Introduction](#introduction)
2. [System Overview](#system-overview)
3. [Technical Specifications](#technical-specifications)
4. [Getting Started](#getting-started)
5. [User Roles and Permissions](#user-roles-and-permissions)
6. [Features and Functionality](#features-and-functionality)
7. [How to Use the System](#how-to-use-the-system)
8. [Recommended Improvements](#recommended-improvements)
9. [Troubleshooting](#troubleshooting)
10. [Support and Contact](#support-and-contact)

---

## 1. Introduction

### 1.1 Purpose
This user manual provides comprehensive documentation for the UTB HR Central system, a web-based Human Resources Management System designed for Universiti Teknologi Brunei. The system facilitates various HR operations including staff profile management, Continuing Professional Development (CPD) applications, memo requests, job postings, and administrative functions.

### 1.2 Scope
This manual covers:
- System architecture and technical specifications
- User interface navigation
- Feature descriptions and usage instructions
- Role-based access and permissions
- Recommended system improvements

### 1.3 Target Audience
- System Administrators
- HR Staff
- Head of Section
- Regular Staff Members
- IT Support Personnel

---

## 2. System Overview

### 2.1 System Description
UTB HR Central is a comprehensive web-based Human Resources Management System that streamlines HR operations for Universiti Teknologi Brunei. The system provides a centralized platform for managing staff profiles, processing CPD applications, handling memo requests, managing job postings, and various administrative tasks.

### 2.2 Key Features
- **User Profile Management**: Complete staff profile with personal, employment, and spouse information
- **CPD Applications**: Submit and manage Continuing Professional Development applications
- **Memo Requests**: Create and track memo requests for various purposes
- **Job Postings**: View and apply for academic, non-academic, and Tabung staff positions
- **Administrative Functions**: User management, approval workflows, and system administration
- **Document Management**: Download forms and manage application documents

---

## 3. Technical Specifications

### 3.1 Programming Languages and Frameworks

#### Backend Technologies
- **PHP 8.2+**: Server-side programming language
- **Laravel Framework 12.0**: Modern PHP framework for web application development
  - MVC (Model-View-Controller) architecture
  - Eloquent ORM for database operations
  - Blade templating engine
  - Built-in authentication and authorization

#### Frontend Technologies
- **HTML5**: Markup language for web pages
- **CSS3**: Styling and layout
- **JavaScript**: Client-side interactivity
- **Tailwind CSS 4.0**: Utility-first CSS framework for responsive design
- **Vite 6.2.4**: Modern build tool for frontend assets

#### Database
- **MySQL**: Relational database management system
  - Used for storing all application data
  - Tables include: users, cpd_applications, job_postings, memo_requests, etc.

#### Additional Libraries and Tools
- **DomPDF (barryvdh/laravel-dompdf)**: PDF generation library for generating application forms and documents
- **Axios**: HTTP client for API requests
- **Composer**: PHP dependency manager
- **NPM**: Node.js package manager for frontend dependencies

### 3.2 System Architecture

#### MVC Pattern
The system follows the Model-View-Controller (MVC) architectural pattern:

- **Models**: Located in `app/Models/`
  - User.php
  - CpdApplication.php
  - JobPosting.php
  - MemoRequest.php
  - JobApplication.php
  - CpdRecommendation.php

- **Views**: Located in `resources/views/`
  - Blade templates for rendering HTML
  - Organized by feature (cpd, memo, admin, etc.)

- **Controllers**: Located in `app/Http/Controllers/`
  - Handle HTTP requests
  - Process business logic
  - Return responses

#### Middleware
- **Authentication Middleware**: Ensures users are logged in
- **Admin Middleware**: Restricts access to administrators
- **Head of Section Middleware**: Restricts access to Head of Section role
- **Profile Completion Middleware**: Ensures user profile is complete before accessing certain features

### 3.3 File Structure
```
internutb/
├── app/
│   ├── Http/
│   │   └── Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Middleware/          # Custom middleware
├── database/
│   ├── migrations/          # Database schema migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   └── css/                 # Stylesheets
├── routes/
│   └── web.php              # Web routes
├── public/                   # Public assets
└── config/                   # Configuration files
```

---

## 4. Getting Started

### 4.1 System Access
1. Open a web browser (Chrome, Firefox, Edge, or Safari)
2. Navigate to the UTB HR Central URL provided by your administrator
3. You will be directed to the login page

### 4.2 User Registration
1. Click on "Sign Up" or "Register" link
2. Fill in the required information:
   - Name
   - Email address
   - Password (must meet security requirements)
   - Confirm password
3. Submit the registration form
4. Wait for administrator approval
5. Once approved, you can log in to the system

### 4.3 Login Process
1. Enter your registered email address
2. Enter your password
3. Click "Login" button
4. Upon successful login, you will be redirected to the dashboard

### 4.4 First-Time Setup
After logging in for the first time:
1. Complete your profile (required fields must be filled)
2. Navigate to "Profile" from the sidebar menu
3. Fill in all required information:
   - Personal Information
   - Employment Information
   - Spouse Information (optional)
4. Click "Update Profile" to save

---

## 5. User Roles and Permissions

### 5.1 Role Types

#### Administrator
- Full system access
- User management (approve, reject, delete users)
- Manage job postings
- Review and approve/reject CPD applications
- Review and approve/reject memo requests
- Access to all administrative functions
- View all applications and requests

#### Head of Section
- Review CPD applications from staff members
- Create recommendations for CPD applications
- Review and approve/reject memo requests
- Access to section-specific data

#### Regular Staff
- Create and manage own profile
- Submit CPD applications
- Create memo requests
- Apply for job postings
- View own applications and requests
- Download forms and documents

### 5.2 Permission Matrix

| Feature | Administrator | Head of Section | Regular Staff |
|---------|--------------|-----------------|---------------|
| View Dashboard | ✓ | ✓ | ✓ |
| Manage Profile | ✓ | ✓ | ✓ |
| Submit CPD Application | ✓ | ✓ | ✓ |
| Review CPD Applications | ✓ | ✓ | ✗ |
| Create Recommendations | ✗ | ✓ | ✗ |
| Submit Memo Request | ✓ | ✓ | ✓ |
| Review Memo Requests | ✓ | ✓ | ✗ |
| View Job Postings | ✓ | ✓ | ✓ |
| Create Job Postings | ✓ | ✗ | ✗ |
| Manage Users | ✓ | ✗ | ✗ |
| Approve/Reject Applications | ✓ | ✓ | ✗ |

---

## 6. Features and Functionality

### 6.1 Dashboard
The dashboard is the main landing page after login, providing:
- Quick access to all system features
- Overview of pending items
- Navigation cards for different modules
- User information display

### 6.2 Profile Management

#### Personal Information
- Full Name (Required)
- IC Number (Required)
- IC Colour (Required)
- Sex (Required)
- Marital Status (Required)
- Date of Birth (Required)
- Place of Birth
- Email (Required)
- Mobile Phone Number (Required)
- Citizenship (Required)
- Address of Country of Origin
- Address in Brunei Darussalam (Required)
- Passport Number (Not Required)

#### Employment Information
- Position (Required)
- Faculty (Required)
- Department (Required)
- Employment Type (Required)
- Salary Scale
- Current Salary
- Duration of Appointment (Contract Staff)

#### Spouse Information (All Optional)
- Spouse Full Name
- Spouse IC Number
- Spouse Citizenship
- Spouse Workplace
- Spouse Position
- Spouse Department
- Spouse Address (Country of Origin)
- Spouse Address (Brunei)

**Note**: Profile completion status is displayed until all required fields are completed.

### 6.3 CPD Applications

#### Creating a CPD Application
1. Navigate to "CPD Applications" from the sidebar
2. Click "New Application" button
3. Fill in all required sections:
   - Applicant Details
   - Event Details
   - Registration Fee Information
   - Paper to be Presented (if applicable)
   - Travel Arrangements
   - Accommodation Details
   - Consultancy Fund Information
4. Upload required documents:
   - Event Brochure
   - Paper Abstract (if applicable)
   - Previous Certificate (if applicable)
   - Publication Paper (if applicable)
   - Travel Documents
   - Accommodation Details
   - Budget Breakdown
   - Other Documents
5. Save as draft or submit for review

#### Application Status
- **Draft**: Application is saved but not submitted
- **Submitted**: Application is submitted and awaiting review
- **Under Review**: Application is being reviewed by Head of Section or Administrator
- **Approved**: Application has been approved
- **Rejected**: Application has been rejected
- **Rework Required**: Application needs corrections before resubmission

#### Viewing Applications
- View all your applications in the CPD Applications list
- Filter by status, event type, or date range
- Search by event title, venue, or organizer
- Download PDF of approved/rejected applications

### 6.4 Memo Requests

#### Creating a Memo Request
1. Navigate to "Memo Requests" from the sidebar or HR Services
2. Click "Create Memo Request" button
3. Fill in the memo details:
   - Title
   - Description
   - Purpose
   - Attach required forms (if applicable)
4. Submit for review

#### Memo Request Status
- **Draft**: Memo is saved but not submitted
- **Submitted**: Memo is submitted and awaiting review
- **Under Review**: Memo is being reviewed
- **Approved**: Memo has been approved
- **Rejected**: Memo has been rejected

### 6.5 Job Postings (Career at UTB)

#### Viewing Job Postings
1. Navigate to "Career at UTB" from the sidebar
2. Browse available positions in three categories:
   - **Academic Positions**: Teaching and research positions
   - **Non-Academic Positions**: Administrative and support positions
   - **Tabung Staff Positions**: Tabung department positions
3. Click on a job posting to view details
4. Click "Apply" to submit an application

#### Applying for a Position
1. Select a job posting
2. Review job requirements and description
3. Click "Apply" button
4. Upload your CV/Resume
5. Fill in additional application details
6. Submit application

#### Viewing Your Applications
- Navigate to "My Applications" to view all your job applications
- Track application status
- Download submitted CV

### 6.6 Service Application (Visa & Pass)

#### Accessing Service Application Forms
1. Navigate to "HR Services" from the dashboard
2. Click on "Service Application" (formerly Visa & Pass)
3. Available forms:
   - Borang Permohonan Visa (Visa Application Form)
   - Permohonan Bagi Satu Pas Tanggungan (Dependent Pass Application Form)
   - Permohonan Kebenaran Tinggal Sementara (Temporary Residence Permit Application Form)
4. Click "Download PDF" to download the required form

### 6.7 Insurance

#### Accessing Insurance Information
1. Navigate to "HR Services" from the dashboard
2. Click on "Insurance"
3. View information about:
   - Available insurance plans
   - Benefits and coverage
   - How to apply for insurance
   - Contact information for insurance inquiries

**Note**: The Insurance section should include promotional content encouraging staff to obtain insurance coverage.

### 6.8 Learning & Development
- Access to training programs
- Workshop information
- Online courses
- Leadership development programs
- Certification programs
- Mentorship programs

---

## 7. How to Use the System

### 7.1 Navigation

#### Sidebar Menu
The sidebar provides quick access to:
- **Homepage**: Return to dashboard
- **Profile**: Manage your profile
- **CPD Applications**: Access CPD features
- **Memo Requests**: Access memo features
- **Career at UTB**: View job postings
- **HR Services**: Access service applications and insurance

#### Top Bar
- Displays HR Central logo
- Shows UTB logo
- User information display

### 7.2 Common Operations

#### Searching and Filtering
Most list pages include:
- **Search Bar**: Search by keywords
- **Status Filter**: Filter by status (e.g., Submitted, Approved, Rejected)
- **Date Range Filter**: Filter by date range
- **Type Filter**: Filter by type (e.g., Event Type, Job Type)

#### Pagination
- Use "Previous" and "Next" buttons to navigate between pages
- Page information displays as "Page X of Y"
- Results are displayed in pages of 15 items

#### Downloading Documents
- Click download buttons to save PDFs or documents
- Documents are generated on-demand
- Ensure pop-up blockers are disabled

### 7.3 Form Submission

#### Best Practices
1. **Complete All Required Fields**: Marked with red asterisk (*)
2. **Upload Required Documents**: Ensure files are in correct format (PDF, DOC, DOCX)
3. **Review Before Submission**: Check all information before submitting
4. **Save Drafts**: Use draft feature to save work in progress
5. **Submit on Time**: Be aware of deadlines for applications

#### File Upload Guidelines
- Maximum file size: Check system limits
- Accepted formats: PDF, DOC, DOCX, JPG, PNG
- File naming: Use descriptive names
- Multiple files: Upload all required documents

---

## 8. Recommended Improvements

This section outlines recommended enhancements to improve system functionality, user experience, and operational efficiency.

### 8.1 Service Application (Visa & Pass Renaming)
**Current State**: The feature is labeled as "Visa & Pass / Dependent"

**Recommended Change**: 
- Rename to "Service Application" throughout the system
- Update all menu items, page titles, and references
- Maintain all existing functionality while using the new terminology

**Implementation Notes**:
- Update route names and controller references
- Modify view files to reflect new naming
- Update database documentation
- Update user-facing text and help documentation

### 8.2 Insurance Section Enhancement
**Current State**: Insurance section exists but may lack promotional content

**Recommended Improvements**:
1. **Add Promotional Content**:
   - Include compelling text encouraging staff to obtain insurance
   - Highlight benefits of having insurance coverage
   - Display success stories or testimonials
   - Show coverage options and advantages

2. **Information Display**:
   - When clicking Insurance, display comprehensive information including:
     - Available insurance plans and packages
     - Coverage details and benefits
     - Premium information
     - How to apply for insurance
     - Step-by-step application process
     - Required documents
     - Contact information for insurance inquiries
     - Frequently Asked Questions (FAQ)

3. **Interactive Features**:
   - Insurance calculator (if applicable)
   - Comparison tool for different plans
   - Application form integration
   - Status tracking for insurance applications

### 8.3 CPD Application Restrictions
**Current State**: No time-based restrictions on CPD applications

**Recommended Improvement**:
- **Add Time Restriction**: Applicants cannot submit CPD applications for events occurring less than one month from the application date
- **Implementation**:
  - Add validation to check event date is at least 30 days in the future
  - Display error message if event is too soon
  - Show minimum date in date picker
  - Update validation rules in CpdController

**Example Validation Message**:
"CPD applications must be submitted at least 30 days before the event date. Please select an event date that is at least one month in the future."

### 8.4 Recommendations Routing to Position Level (PL)
**Current State**: Recommendations may not route to appropriate Position Level

**Recommended Improvement**:
- **Implement PL-Based Routing**: CPD recommendations should be routed to the appropriate Position Level (PL) based on:
  - Applicant's current position level
  - Recommendation type or amount
  - Organizational hierarchy
  - Department structure

**Implementation Requirements**:
1. Add Position Level field to user profile
2. Create PL-based routing rules
3. Implement workflow that routes recommendations to correct PL
4. Add PL information to recommendation display
5. Create notification system for PL reviewers

### 8.5 Approval/Rejection/Rework Reasons
**Current State**: Approvals, rejections, and rework requests may not require reasons

**Recommended Improvement**:
- **Mandatory Reason Fields**: When approving, rejecting, or requesting rework, the approver must provide a reason

**Implementation Details**:
1. **Approval Reasons**:
   - Add "Approval Notes" field (optional but recommended)
   - Allow approver to add comments

2. **Rejection Reasons** (Mandatory):
   - Add "Rejection Reason" text field (required)
   - Provide dropdown of common reasons
   - Allow custom reason input
   - Display rejection reason to applicant

3. **Rework Reasons** (Mandatory):
   - Add "Rework Reason" text field (required)
   - Specify what needs to be corrected
   - Provide guidance for resubmission
   - Display rework reason to applicant

**User Interface Changes**:
- Add reason/notes textarea to approval/rejection/rework forms
- Make rejection and rework reason fields required
- Display reasons in application view
- Send notification emails with reasons

### 8.6 Career at UTB (Jobs Renaming)
**Current State**: Feature is labeled as "Jobs" or "Recruitment"

**Recommended Change**:
- Rename to "Career at UTB" throughout the system
- Update all menu items, page titles, and references
- Maintain all existing functionality

**Implementation Notes**:
- Update route names: `recruitment` → `career-at-utb`
- Modify view files and navigation
- Update controller names and references
- Update database documentation

### 8.7 Additional Recommended Improvements

#### 8.7.1 Enhanced Search Functionality
- Implement advanced search with multiple criteria
- Add saved search functionality
- Include full-text search capabilities

#### 8.7.2 Notification System
- Email notifications for application status changes
- In-app notification center
- SMS notifications for critical updates (optional)

#### 8.7.3 Reporting and Analytics
- Dashboard analytics for administrators
- Application statistics and trends
- Export functionality for reports
- Custom report generation

#### 8.7.4 Mobile Responsiveness
- Ensure all features work on mobile devices
- Optimize forms for mobile input
- Touch-friendly interface elements

#### 8.7.5 Document Management
- Centralized document repository
- Version control for documents
- Document expiration tracking
- Automated document reminders

#### 8.7.6 Workflow Automation
- Automated approval workflows
- Escalation rules for overdue reviews
- Automated reminders for pending actions

#### 8.7.7 Integration Capabilities
- API for external system integration
- Single Sign-On (SSO) support
- Integration with email systems
- Calendar integration for event dates

---

## 9. Troubleshooting

### 9.1 Common Issues

#### Login Problems
**Issue**: Cannot log in to the system
**Solutions**:
- Verify email and password are correct
- Check if account is approved by administrator
- Clear browser cache and cookies
- Try different browser
- Contact administrator if account is locked

#### Profile Completion
**Issue**: Cannot access certain features
**Solutions**:
- Complete all required profile fields
- Check profile completion status
- Ensure all mandatory fields are filled
- Update profile and try again

#### File Upload Issues
**Issue**: Cannot upload files
**Solutions**:
- Check file size (must be within limits)
- Verify file format is accepted
- Ensure stable internet connection
- Try uploading one file at a time
- Contact IT support if problem persists

#### Application Submission
**Issue**: Cannot submit application
**Solutions**:
- Verify all required fields are completed
- Check for validation errors
- Ensure all required documents are uploaded
- Verify event date meets requirements (if time restrictions apply)
- Save as draft and review before submitting

### 9.2 Browser Compatibility
**Supported Browsers**:
- Google Chrome (latest version)
- Mozilla Firefox (latest version)
- Microsoft Edge (latest version)
- Safari (latest version)

**Recommended**: Use the latest version of any supported browser for best experience.

### 9.3 System Requirements
- **Internet Connection**: Stable broadband connection recommended
- **Screen Resolution**: Minimum 1024x768, recommended 1920x1080
- **JavaScript**: Must be enabled
- **Cookies**: Must be enabled
- **Pop-ups**: Allow pop-ups for download functionality

---

## 10. Support and Contact

### 10.1 Getting Help
If you encounter issues or need assistance:
1. Check this user manual first
2. Review the troubleshooting section
3. Contact your system administrator
4. Contact IT support team

### 10.2 Contact Information
- **System Administrator**: [Contact details to be provided]
- **IT Support**: [Contact details to be provided]
- **HR Department**: [Contact details to be provided]

### 10.3 Feedback and Suggestions
We welcome feedback and suggestions for system improvements. Please contact the system administrator or IT support team with your suggestions.

---

## Appendix A: Technical Details

### A.1 Database Schema
Key database tables:
- `users`: User accounts and profile information
- `cpd_applications`: CPD application data
- `cpd_recommendations`: CPD recommendation records
- `job_postings`: Job posting information
- `job_applications`: Job application submissions
- `memo_requests`: Memo request data
- `password_reset_tokens`: Password reset tokens
- `sessions`: User session data

### A.2 Security Features
- Password hashing using bcrypt
- CSRF protection on all forms
- Authentication middleware for protected routes
- Role-based access control
- Input validation and sanitization
- SQL injection prevention through Eloquent ORM

### A.3 API Endpoints
- RESTful API structure
- JSON responses for AJAX requests
- File download endpoints
- PDF generation endpoints

---

## Appendix B: Glossary

- **CPD**: Continuing Professional Development
- **PL**: Position Level
- **IC**: Identity Card
- **MVC**: Model-View-Controller (software architecture pattern)
- **ORM**: Object-Relational Mapping
- **API**: Application Programming Interface
- **CSRF**: Cross-Site Request Forgery
- **SSO**: Single Sign-On

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | November 2025 | System Documentation Team | Initial release |

---

**End of Document**


