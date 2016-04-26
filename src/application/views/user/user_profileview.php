<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); }

$tagList = "";
$index = 1;
foreach ($userTags as $userTag) {
	if ($index > 1) {
		$tagList = $tagList . ", " ;
	}

	$tagList = $tagList . $userTag->TagName;

	$index = $index + 1;
}
?>

<div class="container well">
	<h2 class="page-header"><?php echo $profileInfo->FirstName ?> <?php echo $profileInfo->LastName ?>'s Profile</h2>

	<div class="form-horizontal">
        <!-- Image -->
        <div class="text-center">
            <?php 
                if ($profileInfo->Picture != "") {                     
                    $picture = $GLOBALS["beans"]->fileHelper->getUploadedFileURL('profile', $profileInfo->Picture);
                } else {
                    $picture = URL. "public/img/profile.png";
                }   
            ?>
            <div class="image-frame-profile">
                <div class="image-thumb-profile" style="background-image: url('<?php echo $picture ?>');"></div>
            </div>

        </div>
        <br>
        <div class="row">

            <div class="col-sm-6 col-md-6">
                <h3 class="page-header">Data</h3>

                <div class="form-group">
                    <?php
                        if ($profileInfo->NickName != "") {
                            $nick = $profileInfo->NickName;
                        }
                        else {
                            $nick = $profileInfo->FirstName;
                        }
                    ?>
                    <label class="col-sm-4 control-label">Nick Name:</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $nick ?></p>
                    </div>
                </div>
                <!-- Date -->
                <div class="form-group">
                    <?php
                        if ($profileInfo->FormattedDate != "") {
                            //explode the date to get month, day and year
                            $birthDate = explode("/", $profileInfo->FormattedDate);
                            //get age from date or birthdate
                            $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
                            ? ((date("Y") - $birthDate[2]) - 1)
                            : (date("Y") - $birthDate[2]));
                            $age = $age . " years old";
                        }
                        else {
                            $age = "N/A";
                        }
                    ?>
                    <label class="col-sm-4 control-label">Age:</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $age ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Gender:</label>
                    <div class="col-sm-8">
                        <p class="form-control-static">
                            <?php if ($profileInfo->Gender == "M") { ?>Male <?php } ?>
                            <?php if ($profileInfo->Gender == "F") { ?>Female <?php } ?>
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Email:</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $profileInfo->Email ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <h3 class="page-header">Interests</h3>

                <?php
                    if (count($userTags) > 0) {
                        foreach ($userTags as $userTag) {
                ?>
                            <div class="image-frame"> 
                                <div class="image-thumb" title="<?php echo $userTag->TagName ?>" style="background-image: url('<?php echo URL; ?>public/img/sports/<?php echo $userTag->TagName ?>.png');"></div>
                            </div>

                <?php
                        }
                    }
                    else {
                        echo "currently has no interest.";
                    }

                ?>
                <p></p>
                <h3 class="page-header">Statistics</h3>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Hosted events:</label>
                    <div class="col-sm-8"> 
                        <p class="form-control-static"> <?php echo $countHosted; ?> events</p>
                    </div>
                    <label class="col-sm-4 control-label">Joined events:</label>
                    <div class="col-sm-8"> 
                        <p class="form-control-static"> <?php echo $countJoined; ?> events</p>
                    </div>
                </div>
                <div class="form-group">
                </div>
                
            </div>
        </div>
        
        
        <h2 class="page-header"></h2>

		<!-- Buttons -->
		<div class="form-group text-center">
			<div class="col-sm-12 col-md-12">
				<button type="button" class="btn btn-default" onClick="javascript: history.back()">Back</button>
			</div>
		</div>
	</div>
</div>