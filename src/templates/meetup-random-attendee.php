<? $this->layout('layout'); ?>

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

<form class="form-horizontal" method="post">
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