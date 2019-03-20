<!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="margin:0 0.3em;padding:0;">
                        <h5 class="modal-title" id="exampleModalLongTitle">Vyhledejte své dog friendly místa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="margin:0;padding:0;">
                        <div id="mapContainer" style="width:100%;height:400px;"></div>
                        <?php
                            $db = new Db();
                            $result = $db->fetch_all("SELECT ID, name, address, lat, lng, permalink FROM zarizeni");
                            if($result) {
                                // echo json_encode($result);
                            }
                        ?>
                        <script>
                            function myMap() {
                                var mapProp= {
                                    center:new google.maps.LatLng(50.0755, 14.4378),
                                    zoom:11,
                                };

                                var result = <?php echo json_encode($result); ?>;

                                var map = new google.maps.Map(document.getElementById("mapContainer"),mapProp);

                                for (let index = 0; index < result.length; index++) {
                                    var marker = new google.maps.Marker({
                                        position: {
                                            lat: parseFloat(result[index]['lat']) ,
                                            lng: parseFloat(result[index]['lng'])},
                                            map: map,
                                            title: result[index]['name'] + '\n' + result[index]['address']
                                    });
                                    marker.addListener('click', clickMarker);
                                }

                                console.log(result);

                                // var marker = new google.maps.Marker({position: {lat: parseFloat(result[0]['lat']) , lng: parseFloat(result[0]['lng'])}, map: map});
                            }

                            function clickMarker(event) {
                                console.log(event);

                                var newPath = location.href + 'zarizeni/bernard-pub-u-jezera';

                                location = newPath;
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>