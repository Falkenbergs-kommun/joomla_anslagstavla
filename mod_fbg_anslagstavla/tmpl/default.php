<?php

/**
 * @package    FBG Anslagstavla
 *
 * @author     Tomas Bolling Nilsson <tomas.bollingnilsson@falkenberg.se>
 * @copyright  2022
 * @license    NA
 * @link       falkenberg.se
 */

defined('_JEXEC') or die;

// Access to module parameters
$domain = $params->get('domain', 'https://www.joomla.org');

use Joomla\CMS\Factory;

$user = Factory::getUser();


?>
<style>
	div.fbgtabell {
		font-size: 0.8em;
	}

	#tabell_filter>label>input {
		width: auto;
	}
</style>
<script>
	var todayDate = '<?= date('Y-m-d', time()); ?>';
	var userName = '<?= $user->name; ?>';
	var userEmail = '<?= $user->email; ?>';
</script>
<p uk-margin>
	<button id="btn-addnotice" class="uk-button uk-button-primary">Skapa nytt anslag</button>
</p>
<div class="fbgtabell">
	<table id="tabell" class="uk-table uk-table-small uk-table-striped uk-table-hover uk-margin-medium-top">
		<thead>
			<tr>
				<th scope="col">Rubrik</th>
				<th scope="col">Typ</th>
				<th scope="col">Status</th>
				<th scope="col">Skapad</th>
				<th scope="col">Uppdaterad</th>
				<th scope="col">Sammanträdesdatum</th>
				<th scope="col">Justerat</th>
				<th scope="col">Uppsättande</th>
				<th scope="col">Nedtagande</th>
				<th scope="col">Plats</th>
				<th scope="col">Kontakt</th>
				<th scope="col">E-post</th>
				<th scope="col">Länk bilaga</th>
				<th scope="col">Länk sekundär</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<div id="fbg-modal" uk-modal>
	<div class="uk-modal-dialog"> <button class="uk-modal-close-default" type="button" uk-close></button>
		<div class="uk-modal-header">
			<h2 class="uk-modal-title">Modal Title</h2>
		</div>
		<div class="uk-modal-body">
			<form id="form-notice" class="uk-form-stacked">

				<div class="uk-margin">
					<label class="uk-form-label" for="form-title">Rubrik</label>
					<div class="uk-form-controls">
						<input name="form-title" class="uk-input" id="form-title" type="text" required>
					</div>
				</div>

				<div class="uk-flex uk-flex-top" uk-grid>
					<div>
						<div class="uk-form-label">Typ av anslag</div>
						<div class="uk-form-controls">
							<label><input class="uk-radio" type="radio" name="form-type" value="1"> Protokoll</label><br>
							<label><input class="uk-radio" type="radio" name="form-type" value="2"> Kungörelse</label><br>
							<label><input class="uk-radio" type="radio" name="form-type" value="3"> Underrättelse</label>
						</div>
					</div>

					<div>
						<div class="uk-form-label">Status</div>
						<div class="uk-form-controls">
							<label><input class="uk-radio" type="radio" name="form-published" value="1"> Publicerad</label><br>
							<label><input class="uk-radio" type="radio" name="form-published" value="2"> Arkiverad</label><br>
							<label><input class="uk-radio" type="radio" name="form-published" value="0"> Avpublicerad</label><br>
						</div>
					</div>
				</div>




				<div class="uk-margin">
					<label class="uk-form-label" for="form-noticedatemeeting">Sammanträdesdatum</label>
					<div class="uk-form-controls">
						<input name="form-noticedatemeeting" class="uk-input" id="form-noticedatemeeting" type="date">
					</div>
				</div>

				<div class="uk-margin">
					<label class="uk-form-label" for="form-noticedateadjusted">Datum protokollet är justerat</label>
					<div class="uk-form-controls">
						<input name="form-noticedateadjusted" class="uk-input" id="form-noticedateadjusted" type="date">
					</div>
				</div>

				<div class="uk-margin">
					<label class="uk-form-label" for="form-noticedateposted">Datum för anslagets uppsättande</label>
					<div class="uk-form-controls">
						<input name="form-noticedateposted" class="uk-input" id="form-noticedateposted" type="date">
					</div>
				</div>

				<div class="uk-margin">
					<label class="uk-form-label" for="form-noticedateremoval">Datum för anslagets nedtagande</label>
					<div class="uk-form-controls">
						<input name="form-noticedateremoval" class="uk-input" id="form-noticedateremoval" type="date">
					</div>
				</div>

				<div class="uk-margin">
					<label class="uk-form-label" for="form-noticedocumentlocation">Förvaringsplats för protokollet</label>
					<div class="uk-form-controls">
						<input name="form-noticedocumentlocation" class="uk-input" id="form-noticedocumentlocation" type="text">
					</div>
				</div>

				<div class="uk-margin">
					<label class="uk-form-label" for="form-noticecontactperson">Kontakt</label>
					<div class="uk-form-controls">
						<input name="form-noticecontactperson" class="uk-input" id="form-noticecontactperson" type="text">
					</div>
				</div>

				<div class="uk-margin">
					<label class="uk-form-label" for="form-noticecontactemail">Kontakt e-post</label>
					<div class="uk-form-controls">
						<input name="form-noticecontactemail" class="uk-input" id="form-noticecontactemail" type="email">
					</div>
				</div>

				<div class="uk-margin">
					<label class="uk-form-label" for="form-noticeattachment">Bilaga / Länk</label>
					<div class="uk-form-controls">
						<input name="form-noticeattachment" class="uk-input" id="form-noticeattachment" type="text">
					</div>
				</div>

				<div class="uk-margin">
					<label class="uk-form-label" for="form-noticelink">Sekundär länk</label>
					<div class="uk-form-controls">
						<input name="form-noticelink" class="uk-input" id="form-noticelink" type="text">
					</div>
				</div>

				<div class="js-upload uk-placeholder uk-text-center">
					<span uk-icon="icon: cloud-upload"></span>
					<span class="uk-text-middle">Bifoga dokument genom att släppa filen här eller </span>
					<div uk-form-custom>
						<input type="file">
						<span class="uk-link">bläddra</span>
					</div>
				</div>

				<progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>

				<script>
					var bar = document.getElementById('js-progressbar');

					UIkit.upload('.js-upload', {

						url: '/api/anslagstavla/postAcceptor.php',

						beforeSend: function() {
							console.log('beforeSend', arguments);
						},
						beforeAll: function() {
							console.log('beforeAll', arguments);
						},
						load: function() {
							console.log('load', arguments);
						},
						error: function() {
							UIkit.notification({
								message: 'Något gick fel med uppladdningen',
								status: 'danger'
							});
							console.log('error', arguments);
						},
						complete: function() {
							console.log('complete', arguments);
						},

						loadStart: function(e) {
							console.log('loadStart', arguments);

							bar.removeAttribute('hidden');
							bar.max = e.total;
							bar.value = e.loaded;
						},

						progress: function(e) {
							console.log('progress', arguments);

							bar.max = e.total;
							bar.value = e.loaded;
						},

						loadEnd: function(e) {
							console.log('loadEnd', arguments);

							bar.max = e.total;
							bar.value = e.loaded;
						},

						completeAll: function() {
							var response = JSON.parse(arguments[0].response);
							if (response.url) {
								jQuery('#form-noticeattachment').val(response.url);
								UIkit.notification({
									message: 'Filuppladdning klar',
									status: 'success'
								});
							} else {
								UIkit.notification({
									message: 'Fel: ' + response.message,
									status: 'warning'
								});
							}

							console.log('completeAll', response);
							setTimeout(function() {
								bar.setAttribute('hidden', 'hidden');
							}, 1000);
						}

					});
				</script>
			</form>
		</div>
		<div class="uk-modal-footer uk-text-right"> <button class="uk-button uk-button-default uk-modal-close" type="button">Stäng</button>
			<button id="btn-save" class="uk-button uk-button-primary" type="button">Spara</button>
		</div>
	</div>
</div>