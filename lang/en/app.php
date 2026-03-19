<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Language Lines
    |--------------------------------------------------------------------------
    */

    'app_name' => 'Build Diary',

    // Navigation
    'navigation' => [
        'projects' => 'Projects',
        'people' => 'People',
        'tags' => 'Tags',
        'calendar' => 'Calendar',
        'statuses' => 'Statuses',
        'settings' => 'Settings',
    ],

    // Projects
    'project' => [
        'label' => 'Project',
        'plural' => 'Projects',
        'title' => 'Title',
        'slug' => 'Slug',
        'description' => 'Description',
        'category' => 'Category',
        'status' => 'Status',
        'priority' => 'Priority',
        'due_date' => 'Due Date',
        'started_at' => 'Started At',
        'completed_at' => 'Completed At',
        'is_archived' => 'Archived',
        'metadata' => 'Metadata',
        'person' => 'Associated Person',
        'user' => 'User',
        'categories' => [
            'carpentry' => 'Carpentry',
            '3d_printing' => '3D Printing',
            'paper_art' => 'Paper Art',
            'electronics' => 'Electronics',
            'sewing' => 'Sewing',
            'crafts' => 'Crafts',
            'other' => 'Other',
        ],
    ],

    // Project Files
    'project_file' => [
        'label' => 'File',
        'plural' => 'Files',
        'filename' => 'Filename',
        'path' => 'Path',
        'description' => 'Description',
        'mime_type' => 'Type',
        'size' => 'Size',
        'uploaded_at' => 'Uploaded',
    ],

    // Diary Entries
    'diary_entry' => [
        'label' => 'Diary Entry',
        'plural' => 'Diary Entries',
        'title' => 'Title',
        'content' => 'Content',
        'type' => 'Type',
        'entry_date' => 'Date',
        'time_spent' => 'Time',
        'time_spent_minutes' => 'Time (minutes)',
        'types' => [
            'note' => 'Note',
            'progress' => 'Progress',
            'milestone' => 'Milestone',
            'issue' => 'Issue',
            'solution' => 'Solution',
        ],
    ],

    // Project Links
    'project_link' => [
        'label' => 'Link',
        'plural' => 'Links',
        'title' => 'Title',
        'url' => 'URL',
        'description' => 'Description',
    ],

    // People
    'person' => [
        'label' => 'Person',
        'plural' => 'People',
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'birthday' => 'Birthday',
        'notes' => 'Notes',
        'relationship' => 'Relationship',
        'relationships' => [
            'family' => 'Family',
            'friend' => 'Friend',
            'colleague' => 'Colleague',
            'client' => 'Client',
            'other' => 'Other',
        ],
    ],

    // Tags
    'tag' => [
        'label' => 'Tag',
        'plural' => 'Tags',
        'name' => 'Name',
        'slug' => 'Slug',
        'color' => 'Color',
        'description' => 'Description',
    ],

    // Project Statuses
    'project_status' => [
        'label' => 'Project Status',
        'plural' => 'Project Statuses',
        'name' => 'Name',
        'slug' => 'Slug',
        'color' => 'Color',
        'order' => 'Order',
        'is_default' => 'Default',
        'is_completed' => 'Completed',
    ],

    // Calendar Events
    'calendar_event' => [
        'label' => 'Event',
        'plural' => 'Events',
        'title' => 'Title',
        'description' => 'Description',
        'type' => 'Type',
        'event_date' => 'Date',
        'event_time' => 'Time',
        'end_date' => 'End Date',
        'is_all_day' => 'All Day',
        'is_recurring' => 'Recurring',
        'color' => 'Color',
        'reminder_enabled' => 'Reminder',
        'reminder_minutes_before' => 'Minutes Before',
        'types' => [
            'deadline' => 'Deadline',
            'birthday' => 'Birthday',
            'custom' => 'Custom',
            'reminder' => 'Reminder',
        ],
    ],

    // Dashboard
    'dashboard' => [
        'title' => 'Dashboard',
        'stats' => [
            'active_projects' => 'Active Projects',
            'total_projects' => 'Total Projects',
            'people' => 'People',
            'diary_entries' => 'Diary Entries',
            'upcoming_events' => 'Upcoming Events',
            'hours_worked' => 'Hours Worked',
            'in_progress' => 'In Progress',
            'all_projects' => 'All Projects',
            'registered_contacts' => 'Registered Contacts',
            'total_entries' => 'Total Entries',
            'next_7_days' => 'Next 7 Days',
            'total_logged' => 'Total Logged',
        ],
        'recent_projects' => 'Recent Projects',
        'upcoming_birthdays' => 'Upcoming Birthdays',
    ],

    // Common
    'common' => [
        'created_at' => 'Created',
        'updated_at' => 'Updated',
        'deleted_at' => 'Deleted',
        'actions' => 'Actions',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'create' => 'Create',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'search' => 'Search',
        'filter' => 'Filter',
        'yes' => 'Yes',
        'no' => 'No',
    ],

    // Notifications
    'notifications' => [
        'event_reminder' => [
            'subject' => 'Reminder: :title',
            'greeting' => 'Hello!',
            'line1' => 'This is a reminder for your event: :title',
            'line2' => 'Date: :date - Time: :time',
            'action' => 'View Calendar',
            'salutation' => 'Have a great day!',
        ],
        'birthday_reminder' => [
            'subject' => ':name\'s Birthday is Coming',
            'greeting' => 'Hello!',
            'line1' => ':name\'s birthday is :days',
            'line2' => 'Date: :date (turning :age years old)',
            'action' => 'View Contact',
            'salutation' => 'Don\'t forget to wish them!',
            'today' => 'today',
            'in_days' => 'in :days days',
        ],
    ],

    // Preferences page
    'preferences' => 'Preferences',
    'preferences_page' => [
        'title' => 'Preferences',
        'navigation_group' => 'Settings',
        'appearance' => 'Appearance',
        'appearance_description' => 'Customize the visual appearance of the panel',
        'theme' => 'Theme',
        'theme_light' => 'Always use light theme',
        'theme_dark' => 'Always use dark theme',
        'theme_system' => 'Use your operating system preference',
        'sidebar_collapsed' => 'Sidebar collapsed by default',
        'sidebar_collapsed_helper' => 'Start with the sidebar minimized',
        'projects_section' => 'Projects',
        'projects_section_description' => 'Project view settings',
        'projects_per_page' => 'Projects per page',
        'show_completed_tasks' => 'Show completed tasks',
        'show_completed_tasks_helper' => 'Show completed tasks in task lists',
        'notifications_section' => 'Notifications',
        'notifications_section_description' => 'Manage your notification preferences',
        'email_notifications' => 'Email notifications',
        'email_notifications_helper' => 'Receive event and birthday reminders by email',
    ],
    'language' => 'Language',
    'language_description' => 'Select your preferred interface language',
    'preferences_saved' => 'Preferences saved',

    // Public views
    'public' => [
        'view_more_projects' => '← View more projects',
        'priority_low' => 'Low priority',
        'priority_medium' => 'Medium priority',
        'priority_high' => 'High priority',
        'started' => 'Started',
        'due_date' => 'Due date',
        'completed' => 'Completed',
        'dedicated_to' => 'Dedicated to',
        'for' => 'for',
        'checklist' => 'Checklist',
        'tasks' => 'tasks',
        'percent_completed' => ':percent% completed',
        'all_completed' => 'Completed!',
        'budget' => 'Budget',
        'total' => 'Total',
        'spent' => 'Spent',
        'pending' => 'Pending',
        'budget_percent_spent' => ':percent% of budget spent',
        'links' => 'Links',
        'downloadable_files' => 'Downloadable files',
        'download' => 'Download',
        'project_diary' => 'Project Diary',
        'time_dedicated' => ':hours h :minutes m dedicated',
        'published_with' => 'Published with Build Diary',
        'expense_categories' => [
            'material' => 'Materials',
            'tool' => 'Tools',
            'consumable' => 'Consumables',
            'service' => 'Services',
            'other' => 'Other',
        ],
        'entry_types' => [
            'progress' => 'Progress',
            'issue' => 'Issue',
            'solution' => 'Solution',
            'milestone' => 'Milestone',
            'note' => 'Note',
        ],
        'image_gallery' => 'Image Gallery',
        'tasks_completed' => ':completed / :total tasks completed',
        'spending_progress' => 'Spending progress',
        'generated_on' => 'Generated on :date',
        'scan_to_view_online' => 'Scan to view online',
        'download_pdf' => 'PDF',
        'download_zip' => 'ZIP',
        'back_to_home' => '← Back to home',
        'projects_of' => 'Projects by :name',
        'gallery_meta_description' => 'Public projects gallery by :name',
        'public_projects_count' => ':count public project|:count public projects',
        'no_public_projects' => 'No public projects',
        'no_public_projects_desc' => 'This user has no public projects yet.',
        'tagline' => 'Document your projects step by step',
        'description' => 'Description',
        'expense_item' => 'Item',
        'expense_category_header' => 'Category',
        'expense_quantity' => 'Quantity',
    ],

];
