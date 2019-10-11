<div class="container-fluid ahm-container">
    <div
            class="row about-project"
            ng-controller="AboutProjectController"
            ng-init="initData()"
    >
        <div class="col-xs-12 col-sm-12">

            <!--<h2 class="section-title">O projekcie</h2>-->

            <div class="row paragraf row-1">

                <div class="col-xs-12 col-sm-12 col-md-4 col-image">
                    <img src="{{ asset('imgs/1.jpg') }}" class="img-responsive" ng-click="openAboutGallery(0)">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-text justify-section">
                    <p>
                        Na portalu www.relacjebiograficzne.pl  prezentować będziemy wybrane relacje
                        pochodzące z Archiwum Historii Mówionej DSH i Ośrodka KARTA.
                    </p>
                    <p>
                        AHM to największy w Polsce zbiór wywiadów biograficznych
                        (ponad 5 500 nagrań audio i 122 wideo),
                        obejmujący tematycznie niemal cały XX wiek.
                        W AHM znajdują się wspomnienia na temat międzywojnia, II wojny światowej,
                        czasów stalinizmu, Peerelu i okresu przełomu.
                        Trafiają tutaj nagrania ze wszystkich projektów realizowanych przez DSH i Ośrodek KARTA.
                    </p>
                    <p>
                        Od wielu lat staramy się w miarę szeroko udostępniać materiał archiwalny w internecie,
                        do tej pory prezentując głównie biogramy nagranych osób oraz streszczenia i fragmenty relacji audio i wideo
                        (poprzez stronę <a href="http://www.audiohistoria.pl" target="_blank">www.audiohistoria.pl</a>). Zdecydowaliśmy jednak pójść o krok dalej i jako pierwsi w Polsce
                        zaprezentować pełny źródłowy zapis relacji w formacie audio lub wideo. Wszystkie zapisy źródłowe dodatkowo
                        połączone zostały z transkrypcjami, które ułatwiają przeszukiwanie wywiadów.
                        Portal będzie stale uzupełniany o kolejne relacje.
                    </p>
                </div>


            </div>


            <div class="row paragraf row-2">

                <div class="col-xs-12 col-sm-12 col-md-6 col-text justify-section">
                    <p>
                        Relacje biograficzne to linearne, subiektywne narracje o całościowym indywidualnym doświadczeniu.
                        Świadkowie opowiadają o swoich przeżyciach od czasów dzieciństwa, okresu dorastania,
                        przez kluczowe dla nich doświadczenie wojenne, po teraźniejszość.
                        Na Portalu udostępniamy wybrane wywiady możliwie przekrojowo prezentujące indywidualną pamięć o historii Polski w XX wieku.
                        Pochodzą one z bardzo różnych projektów realizowanych przez zespół AHM w latach 2003-2015.
                        W ten sposób chcieliśmy pokazać różnorodność i bogactwo tego zbioru.
                        Wywiady koncentrują się na tematach takich jak represje nazistowskie i sowieckie,
                        losy cywilów i żołnierzy powstania warszawskiego, niepodległościowe podziemie zbrojne w czasie okupacji i po 1945 roku,
                        swoje losy opowiadają żołnierze różnych polskich formacji zbrojnych.
                        Bardzo mocno reprezentowany jest także głos ludności cywilnej, czy też tzw. zwykłych świadków historii,
                        opowiadających o życiu codziennym w różnych częściach przedwojennej i powojennej Polski.
                    </p>
                    <p>
                        Wybrane do portalu relacje to świetne przykłady wywiadów narracyjnych i biograficznych – trwają wiele godzin,
                        rozmówca samodzielnie prowadzi opowieść o swoim życiu, a pytania do niego pojawiają się dopiero pod koniec nagrania.
                        Ale w udostępnionym zbiorze znajdziemy także wywiady, które odbiegają od tych zasad – rozmówca niemal od samego początku odpowiada na pytania nagrywającego,
                        często brakuje barwnej, samodzielnej narracji, gubiona jest chronologia.
                        Nie wartościujemy jednak tych biografii i opowieści, każda z nich jest szczególna, jedyna. Wybierając relacje do portalu,
                        chcieliśmy pokazać, czym może być wywiad biograficzny, jak różną posiadać strukturę i w jaki sposób kształtować się może pamięć świadków historii.
                    </p>



                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-image">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <img src="{{ asset('imgs/2.jpg') }}" class="img-responsive" ng-click="openAboutGallery(1)">
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <img src="{{ asset('imgs/3.jpg') }}" class="img-responsive" ng-click="openAboutGallery(2)">
                        </div>
                        <div class="col-xs-12 col-sm-12 dystans"></div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <img src="{{ asset('imgs/7.jpg') }}" class="img-responsive" ng-click="openAboutGallery(3)">
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <img src="{{ asset('imgs/9.jpg') }}" class="img-responsive" ng-click="openAboutGallery(4)">
                        </div>

                    </div>

                </div>


            </div>


            <div class="row paragraf row-3">

                <div class="col-xs-12 col-sm-12 col-md-6 col-image">

                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <img src="{{ asset('imgs/6.jpg') }}" class="img-responsive" ng-click="openAboutGallery(5)">
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <img src="{{ asset('imgs/4.jpg') }}" class="img-responsive" ng-click="openAboutGallery(6)">
                        </div>

                    </div>


                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-text justify-section">

                    <p>
                        Na przestrzeni wielu lat zgromadziliśmy w AHM już ok. 30
                        tysięcy archiwalnych zdjęć i dokumentów. Staramy się, aby zeskanowane materiały archiwalne towarzyszyły
                        w miarę możliwości każdej relacji nagrywanej przez zespół AHM. Na Portalu prezentujemy  początkowo ok. 1000 z nich.
                        Z jednej strony stanowią one istotne dopełnienie udostępnionych relacji, z drugiej stanowią wartość samą w sobie i ułożone zostały tematycznie.
                    </p>

                    <p>
                        Ze względu na bardzo osobisty charakter relacji, dostęp do nich możliwy jest po wcześniejszym zarejestrowaniu się
                        (podstawowe dane, tj. imię i nazwisko, adres e-mailowy etc.) oraz zalogowaniu.
                        Jednocześnie dostęp ten jest bezpłatny i nieograniczony czasowo.
                        Mamy nadzieję, że dzięki temu ten cenny materiał źródłowy włączony zostanie do obiegu naukowego i popularnonaukowego,
                        a nasz Portal przyczyni się do upowszechnienia historii mówionej jako sposobu zapisywania przeszłości.
                    </p>
                </div>


            </div>


            <div class="row paragraf row-4">

                <div class="col-xs-12 col-sm-12 col-md-8 col-text">
                    <h3>Realizatorzy:</h3>
                    <p>Jarosław Pałka, Maria Buko - koordynacja</p>
                    <p>Iwona Makowska – archiwalia</p>
                    <p>Magda Szymańska – współpraca
                    <p>Rafał Majewski, Robert Radecki – prace programistyczne</p>
                    <p>Maciej Kamiński, Dariusz Krajewski – przygotowanie materiałów audio i wideo</p>
                    <p>Anna Mizikowska, Joanna Rączka, Anna Wolf – redakcja</p>
                    <p>Maria Buczkowska, Aleksandra Ciecieląg, Grzegorz Kaczorowski, Joanna Komperda, Maria Koral, Dominika Kossowska, Dominika Molak, Agnieszka Piasecka, Karolina Sakowska, Magda Szymańska, Marta Szymańska – transkrypcje</p>

                    <p>Kontakt: <a href="mailto:ahm@dsh.waw.pl">ahm@dsh.waw.pl</a></p>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-image">
                    <img src="{{ asset('imgs/5.jpg') }}" class="img-responsive" ng-click="openAboutGallery(7)">
                </div>

            </div>


            <div photo-slider></div>

        </div>
    </div>
</div>