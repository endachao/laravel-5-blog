$(function() {
    var keyMap = {
        96 : 100, 49 : 102, 50 : 104, 51 : 106, 52 : 108, 53 : 110, 54 : 112, 55 : 114, 56 : 116, 57 : 118, 48: 120, 45: 122, 61: 124,
        126: 101, 33 : 103, 64 : 105, 35 : 107, 36 : 109, 37 : 111, 94 : 113, 38 : 115, 42 : 117, 40 : 119, 41: 121, 95: 123, 43: 125,

        113: 74 , 119: 76 , 101: 78 , 114: 80 , 116: 82 , 121: 84 , 117: 86 , 105: 88 , 111: 90 , 112: 92 , 91 : 94 , 93 : 96 , 92 : 98,
        81 : 75 , 87 : 77 , 69 : 79 , 82 : 81 , 84 : 83 , 89 : 85 , 85 : 87 , 73 : 89 , 79 : 91 , 80 : 93 , 123: 95 , 125: 97 , 124: 99,

        97: 52, 115: 54, 100: 56, 102: 58, 103: 60, 104: 62, 106: 64, 107: 66, 108: 68, 59: 70, 39: 72,
        65: 53, 83 : 55, 68 : 57, 70 : 59, 71 : 61, 72 : 63, 74 : 65, 75 : 67, 76 : 69, 58: 71, 34: 73,

        122: 32, 120: 34, 99: 36, 118: 38, 98: 40, 110: 42, 109: 44, 44: 46, 46: 48, 47: 50,
        90 : 33, 88 : 35, 67: 37, 86 : 39, 66: 41, 78 : 43, 77 : 45, 60: 47, 62: 49, 63: 51,
    };
    MIDI.loadPlugin({
        soundfontUrl: "js/MIDI.js/soundfont/", //acoustic_grand_piano-ogg.js",
        //instrument: 1 // (default)
        callback: function() {
            var delay = 0; // play one note every quarter second
            var velocity = 127; // how hard the note hits play the note
            var count1 = 0;
            var count2 = 0;
            MIDI.setVolume(0, 127);
            $('textarea, input').on('keypress', function(e) {
                count1++;
                if(e.which === 32) {
                    var tone = parseInt(Math.random()*(107 - 25) + 25);
                    MIDI.noteOn(0, tone, velocity, delay);
                    MIDI.noteOff(0, tone, delay + 0.75);
                } else {
                    var tone = keyMap[e.which] - 13;
                    //+ MIDI.pianoKeyOffset;
                    MIDI.noteOn(0, tone, velocity, delay);
                    MIDI.noteOff(0, tone, delay + 0.75);
                }
            });

            $('textarea, input').on('input', function(e) {
                count2++;
                if(count1 !== count2) {
                    var tone = parseInt(Math.random()*(107 - 25) + 25);
                    MIDI.noteOn(0, tone, velocity, delay);
                    MIDI.noteOff(0, tone, delay + 0.75);
                    count1 = 0;
                    count2 = 0;
                }
            });
        }
    });
});
