# Web Instagram - 1155143402 Ng Kin Pak

## Introduction
This project involves implementing an online photo editor and an album for photos uploaded to the system, referred to as "Web Instagram". 

## Website Link
The website for this project is hosted on the Render server. You can access it [here](<https://one155143402-csci4140.onrender.com/index.php>). <br />
Login Credentials  (username and pd are the same):
  - Admin account: admin01
  - User account: user01

## Directory Structure
Explain the purpose and functionality of each directory and its corresponding files in your project.

- `web`: all the source code for this webpage is stored here
  - `index.php`: The index page that provides two functions: displaying the stored photos, edit photo and an interface that allows the logined user to upload a photo.
  - `initialization.php`: An optional interface for the administrator to confirm the initialization procedure.
  - `login.php`: Implements a login program and uses a cookie to authenticate a user session.
  - `photo_editor.php`:  The editor that shows the original photo and the modified photo if a filter is applied.
  - `lib`: directory
  - `Resourcs`: directory

  
- `web/lib`: This directory stores the libraries used in the request and respond stage.
  - `db_connect.php`: Connects to the PostgreSQL database and perform database operation.
  - `process.php`: Processes the user's input and actions by calling the database operation, the controller of the backend program.
  - `edited_iamge.php`: Resrouce path, return the modified photo if a filter is applied.
  - `image.php`: Resrouce path, return the photo stored in the database.
  - `sutilitie`: directory
  
- `web/lib/sutilitie`: This directory stores utility functions.
  - `sanitization.php`: Sanitizes every input from the browser.
  - `validation.php`: Validates every input from the browser by regular expression.

- `web/Resourcs`: the directory store all the static resources e.g., icon of the website 

## Building the System
The system is constructed solely using PHP, HTML, and CSS (Bootstrap). Render serves as the primary development platform. It utilizes Docker for application deployment and offers a complimentary PostgreSQL instance. Additionally, the standard PHP library, ImageMagick, is employed to generate filtered images.




## Completed Parts and Bonus Request
- Bonus parts: 
  - Input Validation: For the login section, the program employs HTML 5 validation (pattern and required) and backend validation to ensure that invalid characters, such as '$', are not accepted. Furthermore, the query string in the URL and data in the body are validated against the backend database before being used as parameters for the function. (Refer to login.php for more details)
  - System Initialization: Upon clicking the 'initialize' button by an admin user, the image database will be cleared. However, user information and session status will remain intact. (Refer to initialization.php for more details)
  - Image File Type Verification: The image type, including the file type name and actual file type, is restricted to jpg, git, and png formats. (Refer to the function store_file($file) in db_connect.php for more details)
  - This readme file

- Parts completed
  - Access control: three type of user: guest, normal **user (username & password: user01)  and **admin user (username & password: admin01) 
    1. Both normal and admin users have the ability to upload, edit photos, and view both private and public images. However, guests are only permitted to view public images.  <br />
    2.  The admin user has the additional capability to initialize the entire image database system. <br />
    3. The login, loginout and authentication system is managed by several functions including: is_auth(), csci4140_login(), csci4140_logout(), and is_admin($username), among others. <br />
    4. Both the cookies (login name and pd) and session (session id) are used to store the login status of the user (only keep 1 hour)
    5. After logout (Exit), any user action e.g., upload image will be invalid and redirect to login page.
    6. If the login is failed, message: "User not found, go back to index.php"

  - Image upload
    1. Only logined user can upload image, they can choose to upload public or private image.
    2. Image must be loaded before it is submitted

  - Photo album 
    1. Photos are displayed in chronological order based on their creation time. The order is not affected by the time of modification, with older photos appearing at the end. <br />
    2. Pagination: Each page displays only 8 photos. Users can navigate through the pages using the 'previous' and 'next' buttons to view additional sets of 8 photos. <br />
    3. Files are stored within the database in binary object format. <br />
    4. Photos can be set to either public or private mode, depending on their visibility to guest users. <br />
  
  - File Editor 
    1. Images can be modified by adding a black border and converting them into black and white. (However, I cannot apply the filter to animated gif image) <br />
    2. Once an image is uploaded, the server redirects the user to the photo editor. Here, users can apply filters, save changes, or delete the image. <br />
    3. Once finish button is click, the changes is save and go back to main page. (if user don't click finish and leave the page, we also assume it save the image without filter)




