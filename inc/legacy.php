<?php
class Formulier extends Widget_M {

	//standaard = krant

	public function __construct ($a = array()) {
		parent::__construct($a);
	}

	public function extra_voor_verp () {
		$s = $this->omgeving === "widget" ? " niet-sticky" : "in-pagina";
		$this->css_klassen = $this->css_klassen . " formulier $s";
	}

	public function fs_1() {
		ob_start(); ?>

		<span class='form-intro'>Gegevens worden veilig over SSL verstuurd en niet online bewaard.</span>

		<?php return ob_get_clean();
	}

	public function fs_2() {
		ob_start(); ?>

		<fieldset>
			<legend>Kies je abo.</legend>
			<label>
				<input type='radio' name='abo_type' value='proef'> Proefabonnement. De Socialist 3 maanden voor €3,50.
			</label>
			<label>
				<input type='radio' name='abo_type' value='jaar'> Jaarabonnement. De Socialist 10 nummers voor €17,-
			</label>
			<label>
				<input type='radio' name='abo_type' value='donateur'> Donateur. Minimaal €5,- per maand en De Socialist er gratis bij.
			</label>
		</fieldset>

		<?php return ob_get_clean();
	}

	public function fs_3() {
		ob_start(); ?>

		<fieldset>
			<legend>Personalia</legend>
			<label>Voornaam
				<input type='text' name='voornaam' placeholder='Erik'>
			</label>
			<label>Achternaam
				<input type='text' name='achternaam' placeholder='Bakker'>
			</label>
			<label>Tel. nr.
				<input type='tel' name='tel' placeholder='0612345678'>
			</label>
			<label>E-mail
				<input type='email' name='mail1' placeholder='voorbeeld@mail.nl'>
			</label>
			<label>Bevestig E-mail
				<input type='email' name='mail2' placeholder='voorbeeld@mail.nl'>
			</label>
		</fieldset>

		<?php return ob_get_clean();
	}

	public function fs_4() {
		ob_start(); ?>

		<fieldset>
			<legend>Adres</legend>
			<label>Straat
				<input type='text' name='straat' placeholder='Voormalige Kerkstraat'>
			</label>
			<label>Huisnr.
				<input type='text' name='huisnummer' placeholder='8'>
			</label>
			<label>Postcode
				<input type='text' name='postcode' placeholder='1111 AA'>
			</label>
			<label>Plaats
				<input type='text' name='plaats' placeholder='Bunnik'>
			</label>
		</fieldset>

		<?php return ob_get_clean();
	}

	public function fs_5() {
		ob_start(); ?>

		<fieldset>
			<legend>Financie&euml;l</legend>
			<label><?=!$this->maandtekst ? "Bij donatie, maandbedrag" : $this->maandtekst?>
				<input type='text' name='bedrag' placeholder='€25'>
			</label>
			<label>IBAN
				<input type='text' name='iban' placeholder='18NL91ABNA0417164300'>
			</label>
			<span>Abonnementen e.d. lopen door dus we hebben je machtiging nodig.</span>
			<label><input type='checkbox' name='machtiging'> Ik ga akkoord met een doorlopende machtiging

			</label>
		</fieldset>

		<?php return ob_get_clean();
	}

	public function fs_6() {
		ob_start(); ?>

		<div class='abo-eind'>
			<span class='form-afsluiting'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, null</span>
			<input type='submit' value='<?=(!$this->knoptekst ? "Geef me die krant!" : $this->knoptekst)?>'>
		</div>

		<?php return ob_get_clean();
	}

	public function zet_inhoud () {

		ob_start(); ?>

		<form id='krant-abo'>

			<?php

				echo $this->fs_1();
				echo $this->fs_2();
				echo $this->fs_3();
				echo $this->fs_4();
				echo $this->fs_5();
				echo $this->fs_6();

			 ?>

		</form>

		<?php $this->inhoud = ob_get_clean();

	}
}

class Krant_abo extends Formulier {
	public function __construct ($a = array()) {
		parent::__construct($a);
	}
}

class LidMaatschap extends Formulier {
	public function __construct ($a = array()) {
		parent::__construct($a);
	}

	public function fs_2() {
		ob_start(); ?>

		<fieldset>
			<legend>Kies je lidmaatschap.</legend>
			<label>
				<input type='radio' name='abo_type' value='proef'> Ik ben een werker. Lidmaatschap vanaf €25,- per maand of 6% van het inkomen.
			</label>
			<label>
				<input type='radio' name='abo_type' value='jaar'> Ik ben student of scholier. Lidmaatschap vanaf €5,- per maand.
			</label>
			<label>
				<input type='radio' name='abo_type' value='donateur'> Ik ben geen werker en geen scholier of student. Lidmaatschap euh....
			</label>
		</fieldset>

		<?php return ob_get_clean();
	}
}

