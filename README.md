## The Economy Simulator
This is a multiplayer game where each player owns a company and is attempting to buy out all of the other
companies to win. They do this by manufacturing products and trading in commodities and stock markets in 
order to make the money they need to execute a hostile takeover on another company. Exposes players to
basic economic concepts such as how supply and demand affect security pricing.

## Run the Code

### Non-Technical Guide:
If you want to use this game for your club, class, or you just want to play it for fun, email me at 
[WILL ADD EMAIL WHEN PROJECT IS COMPLETE] and I will either host a game for you on my website or
help you host your own game.

### Technical Guide:
The code found here is configured for use with [WAMPServer](https://sourceforge.net/projects/wampserver/). 
To run the code with WAMPServer, download it and put the code in the www folder, then import the "econ_data.sql"
file (this is just in the repo for this purpose, so the code does not rely on it) in phpmyadmin. 
If you wish to run the code using something other than WAMPServer, you will likely have to reconfigure the
mysqli_connect functions in "login.php", "register.php", "home.php". Once you have completed that, make sure
you set up an admin account as the first account and hide it from the companies list by commenting out all the SQL
queries on the home.php page expect for the game1players and time ones. You do not need to manage this account, it automatically
executes stock/commodities orders when a third party is neede as a game mechanic (ex. the VC firm option at the start).
