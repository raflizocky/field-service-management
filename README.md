# Fieldo - Field Service Management

**Fieldo** is a streamlined system for managing field technicians, tasks, and reports—built for clarity and efficiency. Built with **Laravel Filament**, **Laravel Sanctum**, and **Flutter**.

Need custom features? [Click Here](mailto:work.raflizocky@gmail.com).

<img src="https://github.com/user-attachments/assets/6e951400-fbc8-437f-a4e4-ec0fb6d59910" alt="Web" width="100%">

<img src="https://github.com/user-attachments/assets/fd90105f-2e77-4d5b-895c-f0e7fb7f49b0" alt="Mobile" width="100%">

## Features

### Admin

-   Manage tasks.
-   Manage reports.
-   Manage technicians.

### Technician

-   View and update assigned tasks.
-   Submit reports.

## Installation (Web)

    ```shell
    # create the database (ex: fieldo)

    # clone the project
    git clone https://github.com/raflizocky/field-service-management.git

    # create .env
    cp .env.example .env

    # adjust db .env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=fieldo
    DB_USERNAME=your_mysql_username
    DB_PASSWORD=your_mysql_password

    # important command before running (change to "&&" if using cmd)
    composer update ; composer i ; php artisan key:generate ; php artisan mi:f --seed ; php artisan storage:link ; php artisan icons:cache ; php artisan ser
    ```
    
    # usage
    email   : admin@example.com
    password: password
    ```

## Contributing

If you encounter any issues or would like to contribute to the project, feel free to:

-   Report any [issues](https://github.com/raflizocky/field-service-management/issues).
-   Submit a [pull request](https://github.com/raflizocky/field-service-management/pulls).
-   Participate in [discussions](https://github.com/raflizocky/field-service-management/discussions) for any questions, feedback, or suggestions.

## License

Code released under the [MIT License](https://github.com/raflizocky/field-service-management/blob/main/LICENSE). Attribution appreciated.
