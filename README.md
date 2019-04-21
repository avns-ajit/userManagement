# User Management

Api's to manage Users and groups

# Features
1. Admin can create Users.
2. Admin can delete Users.
3. Admin can create Groups.
4. Admin can delete Groups when they no longer have members.
5. Admin can remove Users from Groups.
6. Admin can add Users to the Group which they aren't already part of

# API Documentation
https://documenter.getpostman.com/view/402595/S1ETSGqK

# API Collection
https://www.getpostman.com/collections/4c7674956fa384a126ad

# Setup
Please create DataBase name : user_management.
Refer to DATABASE_MODEL.png for Database Model.
Run the user_management.sql to create the tables and above is preloaded with relevant Permissions and Admin User.
Admin UserId to start with - 49d324ea-6146-11e9-ad9d-24a074f0655e

# Permissions
USER_CREATE - To Create Users
USER_DELETE - To Delete Users
GROUP_CREATE - To Create Groups
GROUP_DELETE - To Delete Groups
GROUP_ADD - To Add Users to groups
GROUP_REMOVE - To Remove Users from Groups

# Roles
ADMIN
USER
