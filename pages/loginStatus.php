<?phpif ($_SESSION['AdminLoggedIn'] == true  || $_SESSION['StaffLoggedIn'] == true    || $_SESSION['ClientLoggedIn'] == true  ) {    echo '<a  href="/User/logout' . '"><li>' . 'Log out' . '</li></a>';}else {    echo '<a  href="/User/login' . '"><li>' . 'Log in' . '</li></a>';}if ($_SESSION['AdminLoggedIn'] == true) {    echo '<a  href="/User/staffList' . '"><li>' . 'Stafflist' . '</li></a>';    echo '  <li><a href="/User/register">Register User</a></li>';}if ($_SESSION['AdminLoggedIn'] == true ||$_SESSION['StaffLoggedIn'] == true  ) {    echo '  <li><a href="/job/list">All jobs</a></li>';}if ($_SESSION['ClientLoggedIn'] == true) {    echo '  <li><a href="/job/myJobs">My Jobs</a></li>';}?>