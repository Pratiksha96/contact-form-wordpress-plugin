This plugin creates a basic form that will take input from user and send the data to the email address configured in the plugin.

It will also create a table inside your database configured by name - 
    {wpdb-prefix}-queries when you activate your plugin and will persist the data into database as well.

Note - wpdb-prefix is configured when you setup the wordpress environment. You do not need to worry about this value.

Step 1: To use this plugin you need to add it under your plugins directory of a wordpress site. 

The relative path should be - 
${site-name}/wp-content/plugins

You can pull or clone the repository in above location and you will be able to find it under the plugins option on your wordpress website. 
Note - one needs to be logged in to the wordpress to access the dashboard.

Step 2: Under your contact us page you can place text and you should be able to see the form on your webpage - 

[contact_form]