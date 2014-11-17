<?php

// For sanity
$url = '';
$name = '';

// Check the server this is running on isn't intercepting or diverting calls to Meetup.com
$hostsOK = true;
$hosts = file_get_contents('/etc/hosts');
if( strpos($hosts, 'meetup.com') !== FALSE ) {
	$hostsOK = false;
}

if(isset($_GET['url'])) {

	$url = $_GET['url'];
	$url = trim($url, '/') . '/'; // Ensure URL has a trailing slash

	// Validate URL format, so this tool isn't used to hit random sites, and capture the Meetup group name while we're at it
	if (preg_match ('#^https?://www.meetup.com/([^\/]+)/events/[0-9]+/$#', $url, $results)) {

		$groupname = $results[1];

		// URL is valid. Get contents (via cache) ** cache directory is not in the docroot **
		require '../SimpleCache.php';
		$cache = new Gilbitron\Util\SimpleCache();
		$cache->cache_path = '../../cache/';
		$cache->cache_time = 60 * 5;
		$html = $cache->get_data($url, $url);

		// Strip out all instances of links to attendees, and capture the names
		preg_match_all("#<a href=\"http://www.meetup.com/$groupname/members/[0-9]*\">([^<]*)</a>#", $html, $array);
		$names = $array[1];

		// Remove duplicates (i.e. attending convenors), and select one array key randomly
		$selected = array_rand( array_unique( $names ));

		// Extract the value -- as this was ripped from HTML, it is already HTML encoded :)
		$name = $names[$selected];
	}
		
}

?><?php include '../head.php'; ?>

  <h4><a href="/meetup-random-attendee">Meetup.com Random Attendee Selector</a></h4>
  <p>CTO for Hire's Ben Dechrai runs the Melbourne PHP Users Group, and wanted a way to randomly select an attendee for the purposes of giving away prizes. He wrote these seven lines of code and now you too can get a random member of your meetup in our hosted version.</p>

  <?php if (!$hostsOK) : ?>
    <div class="alert alert-danger" role="alert">
      It seems this server's hosts file has been modified to specify the IP address for Meetup.com!
    </div>
  <?php endif; ?>

  <?php if( isset($name) && $name != '' ) : ?>
    <div class="meetup-random-attendee-winner jumbotron">
      <h1><?php echo $name ?></h1>
      <p class="lead">You are a winner!</p>
    </div>
  <?php endif; ?>

  <form class="form-horizontal">
    <fieldset>

      <div class="form-group">
        <label class="col-md-4 control-label" for="url">Meetup.com URL</label>
        <div class="col-md-8">
        <input id="url" name="url" type="text" placeholder="http://www.meetup.com/YOUR-GROUP/events/EVENT_ID/" class="form-control input-md" required="" value="<?php echo htmlspecialchars($url) ?>">
        <span class="help-block">Enter the full URL to the main page of your Meetup.com event</span>
        </div>
      </div>

      <!-- Button -->
      <div class="form-group">
        <label class="col-md-4 control-label" for="go"></label>
        <div class="col-md-4">
          <button id="go" name="go" class="btn btn-primary">Go Spin That Wheel</button>
          <span class="help-block">Event page lookups are cached for 5 minutes</span>
        </div>
      </div>

    </fieldset>
  </form>

  <p><strong>Note:</strong> This tool is provided as-is and without warranty of any sort. It has not been audited for its randomness or effectiveness for running games of chance. You can <a href="index.txt">see the raw PHP file used to generate this page here</a>. Bug reports, feature requests and feedback welcome at <a href="https://twitter.com/ctoforhireco">@ctoforhireco</a>.</p>

<?php include '../foot.php'; ?>
