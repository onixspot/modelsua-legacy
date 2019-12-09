<script src="https://api-maps.yandex.ru/1.1/index.xml?key=AKfcOE8BAAAA1RwlGAIAFkDoi-3nG4A2ZF4iJhblvhDijMQAAAAAAAAAAABbw6MCfhnTxHF_9BO-cwe7kN-QHg==" type="text/javascript"></script>
<script type="text/javascript" src="https://api-maps.yandex.ru/1.1/?key=AKfcOE8BAAAA1RwlGAIAFkDoi-3nG4A2ZF4iJhblvhDijMQAAAAAAAAAAABbw6MCfhnTxHF_9BO-cwe7kN-QHg==&modules=traffics" charset="utf-8"></script>
<script type="text/javascript">
    window.onload = function () {
        var map = new YMaps.Map(document.getElementById("YMapsID"));
        map.setCenter(new YMaps.GeoPoint(30.51567,50.465076), 16);
        map.enableScrollZoom();
        var toolBar = new YMaps.ToolBar();
        var zoomControl = new YMaps.Zoom();
        var typeControl = new YMaps.TypeControl(); 
        var scaleLine = new YMaps.ScaleLine();
        var searchControl = new YMaps.SearchControl({
                                resultsPerPage: 5,  // Количество объектов на странице
                                useMapBounds: 1     // Объекты, найденные в видимой области карты 
                                                    // будут показаны в начале списка
                            });
        map.addControl(toolBar);
        map.addControl(zoomControl);
        map.addControl(typeControl);
        map.addControl(scaleLine);
        map.addControl(searchControl);
        // Создает метку и добавляет ее на карту
        var placemark = new YMaps.Placemark(new YMaps.GeoPoint(30.51567,50.465076));
        placemark.name = '<?=t("Ассоциация моделей Украины (АМУ)")?>';
        placemark.description = "<div class='mt10'><img class='left mr10' src='/m.png' style=' width: 80px; height: 80px;'><div class='left' style='width: 220px;'><?=t("г. Киев")?>,<br/> <?=t("м.Контрактовая площадь")?>,<br/> <?=t("ул. Константиновская 2а, 5 этаж")?>,<br/> <?=t("тел")?>.: (044) 492 94 90<br/> <?=t("факс")?>: (044) 492 92 91</div></div>";
        map.addOverlay(placemark);

        // Открывает балун
        placemark.openBalloon();
    }
</script>
<div class="content_container" style="width: 708px;">
    <div class="inner_container pl10">
        <p>
            <b>
                <?=t("Ассоциация моделей Украины (АМУ)")?>
            </b>
            <br/><br/>
            <span><?=t("ул. Константиновская 2а, 5 этаж")?></span><br/>
            <span><?=t("г. Киев")?>, 04071</span><br/><br/>
            <span><?=t("тел")?>.: (044) 492-94-90</span><br/>
            <span><?=t("факс")?>.: (044) 492 92 91</span><br/>
            <br/>
            <span><a href="/feedback" class="bold"><?=t("Послать сообщение")?></a></span><br/>
        </p>

        <div id="YMapsID" class="mb20" style="width:698px;height:400px"></div>
    </div>
</div>
