# User Management

Api's to manage Users and groups

# Features
1. Admin can create Users.
2. Admin can delete Users.
3. Admin can create Groups.
4. Admin can delete Groups when they no longer have members.
5. Admin can remove Users from Groups.
6. Admin can add Users to the Group which they aren't already part of.

# API Documentation
https://documenter.getpostman.com/view/402595/S1ETSGqK

# API Collection
https://www.getpostman.com/collections/4c7674956fa384a126ad

# Models
1. PLease check DATABASE_MODEL.png for Database Model.
2. Please check UserManagement_Model.png

# Setup
1. Please create Database with name : user_management.
3. Run the user_management.sql to create the tables and it is also preloaded with relevant Permissions and Admin User.
4. Admin UserId to start with - 49d324ea-6146-11e9-ad9d-24a074f0655e.

# Permissions
1. USER_CREATE - To Create Users.
2. USER_DELETE - To Delete Users.
3. GROUP_CREATE - To Create Groups.
4. GROUP_DELETE - To Delete Groups.
5. GROUP_ADD - To Add Users to Groups.
6. GROUP_REMOVE - To Remove Users from Groups.

# Roles
1. ADMIN.
2. USER.

# Tests
./vendor/bin/simple-phpunit 
