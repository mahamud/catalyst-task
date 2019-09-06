# Catalyst Task
PHP script that parses a csv file and insert data to a PostgreSQL Database


# Ideal Environment
The ideal environment to execute the script
1. Ubuntu 18.04
2. PHP Version - 7.2.x
3. PostgreSQL 11.5(or above)


#Command Options
`php user_upload.php`

Running this command will execute the script over and over. Everytime the database table will be re-created and the data freshly inserted.

`php user_upload.php --file=users.csv`

This command will try to use the data file passed within the option. Please note, the system will look for the data file within the "**data**" folder. Only the name of the datafile is required. Not the full path.

`php user_upload.php --create_table`

This command will create the table snd stop the execution of the script once that is done.




#Note
1
