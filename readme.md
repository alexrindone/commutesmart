# Commutesmart SEACOAST
<a href="https://commutesmartseacoast.org/">Commutesmart SEACOAST</a> is a free program that helps commuters find the best way to get to work or school. As part of the program Commutesmart SEACOAST offered three commuter challenges offer a fun way to earn prizes by commuting smart:
    - Commute Smart B2B Challenge,  May 1-31
    - Dump the Pump,  June 1 – August 31
    - Conquer the Cold,  Nov. 1 – Jan. 31
    
This repository is the code for Commutesmart SEACOAST's trip logging application located <a href="https://trips.commutesmartseacoast.org">here</a>. Currently Commutesmart SEACOAST has stopped it's programming but <a href="http://commutesmartnh.org/">Commutesmart NH</a> will be using this trip logging application for the state-wide program.

## Features/Documentation
This application uses Laravel 7.2 PHP framework along with VueJS, Bootstrap CSS, and the Blade templating engine. 
1. Admin Portal for managing challenges
2. Various Challenge Leaderboards (company, individual, and overall)
3. Team Management Portal (for company team captains)
4. User Profile
5. Login Portal
6. Trip Logger

This application follows the MVC software architecture. Custom code highlights include the following:
1. <a href="https://github.com/alexrindone/commutesmart/blob/master/routes/web.php">Web Routes</a>
2. <a href="https://github.com/alexrindone/commutesmart/blob/master/resources/views/layouts/app.blade.php">Main App Template</a>
3. <a href="https://github.com/alexrindone/commutesmart/blob/master/resources/views/auth/register.blade.php">User Registration</a>
4. <a href="https://github.com/alexrindone/commutesmart/tree/master/resources/assets/js/components">Various VueJS components</a>
5. <a href="https://github.com/alexrindone/commutesmart/tree/master/database/migrations">Migration Files for creating MySQL DB Tables</a>
6. <a href="https://github.com/alexrindone/commutesmart/tree/master/app/Mail">Various PHP Mail functions</a>
7. <a href="https://github.com/alexrindone/commutesmart/tree/master/app/Http/Controllers">Various Controller functions</a>

## Issues
Please email any issues, bugs, or additional features you would like to see built to arindone1679@gmail.com.

## Contributing
If you wish to contribute to this package you may fork the repository and make a pull request to this repository.
