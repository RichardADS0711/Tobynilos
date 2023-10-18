<div class="watchContainer">

    <div class="videoControls watchNav">
        <button onclick="goBack()"><i class="fas fa-arrow-left"></i></button>
        <h1>Ruthyliculas</h1>
    </div>


    <div class="videoControls upNext" style="display:none;">

        <button onclick="restartVideo();"><i class="fas fa-redo"></i></button>

        <div class="upNextContainer">
            <button class="playNext">
                <i class="fas fa-play"></i> Play
            </button>
        </div>
    
    </div>

    <video controls autoplay>
        <source src='25.mkv' type="video/mp4">
        <track
            label="Español"
            kind="subtitles"
            srclang="en"
            src="25.vtt"
            default />
    </video>
</div>