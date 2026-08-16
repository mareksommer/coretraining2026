<?php
/**
 * Template: Služby (/sluzby/)
 */

global $coretraining_page_title, $coretraining_meta_description;
$coretraining_page_title       = 'Služby – CoreTraining';
$coretraining_meta_description = 'Funkční diagnostika pohybového aparátu a individuální spolupráce postavená na 25 letech praxe.';

get_header();
?>

<section class="page-hero page-hero--compact">
    <div class="container">
        <h1 class="page-hero__title">Služby</h1>
        <p class="page-hero__lead text-muted">
            Kurzy a články jsou jedna část CoreTraining. Druhá je přímá práce s lidmi — diagnostika pohybu
            a individuální spolupráce postavená na stejných principech: pochopení, ne univerzální šablona.
        </p>
    </div>
</section>

<section class="section service-section" id="diagnostika">
    <div class="container content-block">
        <h2 class="section__title">Funkční diagnostika pohybového aparátu</h2>
        <p>Diagnostika není doplněk k tréninku. Je to druhý krok hned po vstupním dotazníku a základ pro smysluplný postup u každého klienta.</p>
        <p>Cílem není zjistit, kolik máte tuku nebo svalů. Cílem je pochopit, jak se člověk pohybuje, kde jsou asymetrie, dysbalance a rizika — a na tom postavit konkrétní, individuální plán.</p>

        <h3>Pro koho</h3>
        <ul>
            <li>Osobní trenéři a siloví/kondiční kouči</li>
            <li>Fyzioterapeuti na pomezí rehabilitace a tréninku</li>
            <li>Sportovci po zranění nebo při hledání příčiny omezení</li>
            <li>Aktivní lidé, kteří chtějí trénovat s rozmyslem, ne „hurá programem“</li>
        </ul>

        <h3>Co diagnostika zahrnuje</h3>
        <ul>
            <li>Komplexní vstupní dotazník — životní styl, cíle, historie úrazů a bolesti</li>
            <li>Vyšetření pohybových vzorů v sedu, stoje i při pohybu</li>
            <li>Identifikace asymetrií, dysbalancí a rizikových oblastí</li>
            <li>Propojení nálezu s konkrétním tréninkovým nebo rekondičním postupem</li>
            <li>Výstup, se kterým lze dál pracovat — u trenéra, fyzioterapeuta i v individuální spolupráci</li>
        </ul>

        <h3>Proč to dává smysl</h3>
        <p>Většina lidí netrpí tím, že by necvičili dost. Často trpí tím, že cvičí nesprávně — bez pochopení vlastního těla. Univerzální trénink podle šablony může být nejen neefektivní, ale i kontraproduktivní.</p>
        <p>Diagnostika pomáhá oddělit, co je u konkrétního člověka skutečně relevantní, a nastavit cílený postup místo hádání.</p>

        <a href="<?php echo esc_url(home_url('/kontakt/?predmet=Funkční+diagnostika')); ?>" class="btn btn--primary">
            Domluvit diagnostiku
        </a>
    </div>
</section>

<section class="section section--alt service-section" id="spoluprace">
    <div class="container content-block">
        <h2 class="section__title">Individuální spolupráce</h2>
        <p>Více než 25 let praxe v silovém tréninku a funkční diagnostice. Individuální spolupráce není o předepsání sestavy cviků — je o práci s konkrétním člověkem, jeho pohybem, cíli a limitacemi.</p>
        <p>Spolupracuji s trenéry, sportovci i běžnými klienty. Často navazuji na výstup z funkční diagnostiky nebo ve spolupráci s lékařem či fyzioterapeutem.</p>

        <h3>V čem mohu pomoci</h3>
        <div class="content-columns">
            <div>
                <h4>Silový trénink a technika</h4>
                <ul>
                    <li>Praktické vedení individuální tréninkové jednotky</li>
                    <li>Prověření techniky cviků a biomechaniky pohybu</li>
                    <li>Konzultace k vedení tréninkové jednotky i celého cyklu</li>
                    <li>Nastavení silové přípravy dle sportovního zaměření</li>
                </ul>
            </div>
            <div>
                <h4>Návrat k výkonu po bolesti nebo zranění</h4>
                <ul>
                    <li>Rekondice po operaci nebo úrazu s promyšlenou progresí</li>
                    <li>Nápravně-silový plán podle výstupu z diagnostiky</li>
                    <li>Úprava tréninku při bolesti zad, kyčlí nebo ramen</li>
                    <li>Návrat k plnému výkonu — bez zbytečného strachu, s pochopením principů</li>
                </ul>
            </div>
        </div>

        <h3>Formy spolupráce</h3>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Balíček</th>
                        <th>Obsah</th>
                        <th>Cena</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>10 lekcí + diagnostika</td><td>10 individuálních lekcí, komplexní funkční diagnostika</td><td>18 500 Kč</td></tr>
                    <tr><td>10 lekcí</td><td>Bez diagnostiky</td><td>16 000 Kč</td></tr>
                    <tr><td>Měsíční spolupráce (1× týdně) + diagnostika</td><td>4 lekce měsíčně + diagnostika</td><td>8 900 Kč</td></tr>
                    <tr><td>Měsíční spolupráce (1× týdně)</td><td>Bez diagnostiky</td><td>6 400 Kč</td></tr>
                    <tr><td>Měsíční spolupráce (2× týdně) + diagnostika</td><td>8 lekcí měsíčně + diagnostika</td><td>15 300 Kč</td></tr>
                    <tr><td>Měsíční spolupráce (2× týdně)</td><td>Bez diagnostiky</td><td>12 800 Kč</td></tr>
                </tbody>
            </table>
        </div>
        <p class="text-muted">Platnost 10 lekcí: max. 4 měsíce od zakoupení. Konkrétní podmínky a individuální nastavení ceny — po domluvě.</p>

        <div class="content-block__actions">
            <a href="<?php echo esc_url(home_url('/kontakt/?predmet=Individuální+spolupráce')); ?>" class="btn btn--primary">
                Domluvit spolupráci
            </a>
            <p class="contact-inline">
                <a href="tel:<?php echo esc_attr(CORETRAINING_CONTACT_PHONE_LINK); ?>"><?php echo esc_html(CORETRAINING_CONTACT_PHONE); ?></a>
                · <a href="mailto:<?php echo esc_attr(CORETRAINING_CONTACT_EMAIL); ?>"><?php echo esc_html(CORETRAINING_CONTACT_EMAIL); ?></a>
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section__title">Související</h2>
        <ul class="link-list">
            <li><a href="<?php echo esc_url(home_url('/kurzy/')); ?>">Kurzy a semináře</a> — skupinové vzdělávání</li>
            <li><a href="<?php echo esc_url(home_url('/studio/')); ?>">Studio CORE</a> — kde probíhá většina individuální práce</li>
        </ul>
    </div>
</section>

<?php get_footer(); ?>
