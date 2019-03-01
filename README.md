# Skillmatrix
Learning Symfony 4 on "real" project.

## Installation

1. Clone repository:  
`git clone https://github.com/demijohn/skillmatrix.git`

2. Run `composer install` inside project directory

3. Copy .env file to .env.local:    
`cp .env .env.local`  

4. Set database credentials in .env.local file:  
`DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name`  

5. Create database:  
`php bin/console doctrine:database:create`  
NOTE: db_user set in previous step must have privileges to create databases. Otherwise you have to create database manually and skip this step.

6. Run database migrations:  
`php bin/console doctrine:migrations:migrate`

7. Run database fixtures:  
`php bin/console doctrine:fixtures:load`  
or load fixtures for Persons, Reviewers and Skills but not Rating fixtures:    
`php bin/console doctrine:fixtures:load --group=PersonFixtures --group=ReviewerFixtures --group=SkillFixtures`  
This is usefull when Ratings should be edited manually.  

8. Copy phpcs.xml.local to phpcs.xml:  
`cp phpcs.xml.local phpcs.xml`

9. Install frontend libraries:  
`yarn install`

10. Build frontend libraries:  
`yarn dev`
    
## Run Code Standard check  

`./vendor/bin/phpcs -p -s`  

Where:  
-p Display progress  
-s Display sniff name  
