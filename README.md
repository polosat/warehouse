This is a simple online file storage written purely for demonstration and educational purposes.
<br>
No 3rd-party frameworks or components were used.
<p>
Here is the short list of general features:
<ul>
  <li>Authenticated users are able to upload and download files</li>
  <li>A user can not access files belonging to other members</li>
  <li>Up to 20 files can be uploaded to the storage, each one is not more than 1MB in size</li>
  <li>Anyone can create a new user profile</li>
  <li>A user can edit its own profile including the login name</li>
  <li>A user can switch between English and Russian languages</li>
  <li>All major browser are supported (>IE7, >FF3, >CR3)
</ul>
</p>
<p>
Some implementation details:
<ul>
  <li>Application sources are located in the <a href="https://github.com/polosat/warehouse/tree/master/sources/www">/warehouse/sources/www</a> folder</li>
  <li>A simple lightweight MVC framework was implemented to simplify development</li>
  <li>A nice tiny calendar control was created using pure Javascript: <a href="https://github.com/polosat/warehouse/tree/master/sources/www/classes/controls/datepicker">/warehouse/sources/www/classes/controls/datepicker</a></li>
  <li>The PRG pattern is used everywhere across the application</li>
  <li>You can easily deploy the full environment for this project using <a href="https://www.vagrantup.com">vagrant</a> and <a href="https://www.virtualbox.org">virtual box</a></li>
  <li>The <a href="http://codeception.com">Codeception</a> test set is configured and ready for implementation but is not implemented yet :(</li>
  <li>TODO: It would be great to add some additional comments explaining the code ;)</li>
</ul>
</p>
<p>
How to deploy this project?
<ul>
  <li>Install <a href="https://www.virtualbox.org">virtual box</a> and <a href="https://www.vagrantup.com">vagrant</a></li> 
  <li>Clone this repository</li>
  <li>Go to the <b>warehouse/vagrant</b> folder and run the command <b>vagrant up</b></li>
  <li>Take some coffee and wait ;)</li>
  <li>Once the process is finished, you can access the working application at <a href="http://192.168.33.30">http://192.168.33.30</a></li>
</ul>
</p>
