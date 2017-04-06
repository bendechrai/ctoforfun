<?php

if(!file_exists('env.php')) {
	echo 'env.php not found';
	exit;
}
require 'env.php';

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
	if (preg_match ('#^https?://www.meetup.com/([^\/]+)/events/([0-9]+)/$#', $url, $results)) {

		// Build API URL
		$groupname = $results[1];
		$eventid = $results[2];
		$apiurl = "https://api.meetup.com/{$groupname}/events/{$eventid}/rsvps?key={$meetup_api_key}";

		// URL is valid. Get contents (via cache) and parse
		require '../SimpleCache.php';
		$cache = new Gilbitron\Util\SimpleCache();
		$cache->cache_path = '../../cache/';
		$cache->cache_time = 60 * 5;
		$json = $cache->get_data($apiurl, $apiurl);
		$rsvps = json_decode($json, true);

		// Filter our event hosts
		$rsvps = array_filter($rsvps, function($rsvp) {
			return !$rsvp['member']['event_context']['host'];
		});

		// Select one at random
		$rsvp = $rsvps[array_rand($rsvps)];
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

  <?php if( isset($rsvp) ) : ?>
    <div class="meetup-random-attendee-winner jumbotron">
      <h1><?php echo htmlspecialchars($rsvp['member']['name']) ?></h1>
      <p><img src="<?php echo $rsvp['member']['photo']['photo_link'] ?>" /></p>
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

  <p><strong>Note:</strong> This tool is provided as-is and without warranty of any sort. It has not been audited for its randomness or effectiveness for running games of chance. Issues and pull requests welcome at <a href="https://github.com/bendechrai/ctoforfun">github/bendechrai/ctoforfun</a>.</p>

<?php include '../foot.php'; ?>
