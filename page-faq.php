<?php
/**
 * Template for FAQ.
 */

get_header(); ?>

<div class="content">
	<div class="page-padding">

<h1>💡 Frågor & svar</h1>
<hr>
<p class="small">💡 Vanliga frågor och info om hur LOOPIS fungerar.</p>

<p>Här hittar du svar på de vanligaste frågorna om hur LOOPIS fungerar. Vi uppdaterar sidan baserat på vad våra medlemmar undrar över.</p>
<?php if ( is_user_logged_in() ) : ?>
<p>Du som är inloggad kan skicka frågor och feedback till admin längst ner! Om du har frågor om en specifik annons finns ett formulär längst ner i annonsen.</p>
<?php endif; ?>

<h3>Instruktioner</h3>
<hr>
<p><span class="big-link"><a href="hur-funkar-loopis">📌 Hur funkar LOOPIS?</a></span></p>
<p><span class="big-link"><a href="hur-far-jag-saker">📌 Hur får jag saker?</a></span></p>
<p><span class="big-link"><a href="hur-ger-jag-saker/">📌 Hur ger jag saker?</a></span></p>
<p><span class="big-link"><a href="tips-hemskarm">📌 Lägg LOOPIS på din hemskärm</a></span></p>

<?php if ( is_user_logged_in() ) : ?>
<p><span class="big-link"><a href="kop-mynt">💰 Köp regnbågsmynt</a></span></p>
<?php endif; ?>

<h3>Medlemskap</h3>
<hr>
<p><span class="big-link"><a href="varfor-medlemskap">📌 Varför måste jag vara medlem?</a></span></p>
<p><span class="big-link"><a href="varfor-bagis">📌 Varför måste jag bo i Bagis?</a></span></p>
<p><span class="big-link"><a href="tips-till-ny-medlem">📌 Tips till ny medlem</a></span></p>

<?php if ( current_user_can('member_earlier')) { ?>
	<p><span class="big-link"><a href="../fornya-medlemskap">📋 Förnya medlemskap</a></span></p>
<?php } else { ?>
	<p><span class="big-link"><a href="../bli-medlem">📋 Bli medlem</a></span></p>
<?php } ?>

<h3>LOOPIS.app</h3>
<hr>
<p><span class="big-link"><a href="hur-funkar-lottning">📌 Hur funkar lottning?</a></span></p>
<p><span class="big-link"><a href="hur-funkar-regnbagsmynt">📌 Hur funkar regnbågsmynt?</a></span></p>
<p><span class="big-link"><a href="hur-funkar-beloningar/">📌 Hur funkar belöningar?</a></span></p>
<p><span class="big-link"><a href="restriktioner">📌 Vilka annonser är inte tillåtna?</a></span></p>


<h3>LOOPIS skåp</h3>
<hr>
<p><span class="big-link"><a href="hur-funkar-skapet">📌 Hur funkar skåpet?</a></span></p>
<p><span class="big-link"><a href="saker-som-inte-ryms-i-skapet">📌 Saker som inte ryms i skåpet?</a></span></p>

<h3>Föreningen</h3>
<hr>
<p><span class="big-link"><a href="vad-ar-loopis">📌 Vad är LOOPIS?</a></span></p>
<p><span class="big-link"><a href="kontakt">📌 Kontakt med föreningen</a></span></p>
<p><span class="big-link"><a href="hjalpa-till">📌 Hur kan jag hjälpa till?</a></span></p>
<p><span class="big-link"><a href="max-murpos">📌 Vem är Max Murpos?</a></span></p>
<p><span class="big-link"><a href="stadgar">📜 Föreningens stadgar</a></span></p>
<p><span class="big-link"><a href="../privacy">🗄 Integritetspolicy</a></span></p>

<?php if ( is_user_logged_in() ) : ?>
<p><span class="big-link"><a href="https://drive.google.com/drive/folders/1l1B43flky-zXgQ2wFD24s_32N_pfWHvd?usp=drive_link"><i class="fas fa-share"></i> Föreningens protokoll</a></span></p>
<p><span class="big-link"><a href="https://www.facebook.com/groups/loopis" target="_blank" rel="noreferrer noopener"><i class="fas fa-share"></i> Facebook-grupp för medlemmar</a></span></p>
<?php endif; ?>

<?php if ( is_user_logged_in() ) {  ?>
<h3>Skicka fråga!</h3>
<hr>
<p>Skicka en fråga eller feedback till admin:</p>
<div id="support">
<?php echo do_shortcode('[wpum_post_form form_id="3"]'); ?>
</div>
<?php }  ?>

<div style="height:25px" aria-hidden="true" class="wp-block-spacer"></div>

<div class="wrapped faq">
<h5>⚠ Fler frågor?</h5>
<hr>
<p>→ Titta på sidan <a href="/faq">Frågor &amp; svar</a></p>
<?php if ( is_user_logged_in() ) { ?>
<p>→ Fråga i medlemmarnas <a rel="noreferrer noopener" href="https://web.facebook.com/groups/loopis.medlemmar" target="_blank">Facebook-grupp</a></p>
<?php } ?>
<p>→ Maila styrelsen på <a rel="noreferrer noopener" href="mailto:info@loopis.org" target="_blank">info@loopis.org</a></p>
</div>

</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>