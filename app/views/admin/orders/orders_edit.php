
<script type="text/javascript">

function draw_ean( elem, ean )
{
  var A = {'0' : "0001101", '1' : "0011001", '2' : "0010011", '3' : "0111101", '4' : "0100011", 
           '5' : "0110001", '6' : "0101111", '7' : "0111011", '8' : "0110111", '9' : "0001011"};
  var B = {'0' : "0100111", '1' : "0110011", '2' : "0011011", '3' : "0100001", '4' : "0011101",
           '5' : "0111001", '6' : "0000101", '7' : "0010001", '8' : "0001001", '9' : "0010111"};
  var C = {'0' : "1110010", '1' : "1100110", '2' : "1101100", '3' : "1000010", '4' : "1011100",
           '5' : "1001110", '6' : "1010000", '7' : "1000100", '8' : "1001000", '9' : "1110100"};
           
  var groupC = C;

  var family = {'0' : [A,A,A,A,A,A], '1' : [A,A,B,A,B,B], '2' : [A,A,B,B,A,B], '3' : [A,A,B,B,B,A], '4' : [A,B,A,A,B,B],
                '5' : [A,B,B,A,A,B], '6' : [A,B,B,B,A,A], '7' : [A,B,A,B,A,B], '8' : [A,B,A,B,B,A], '9' : [A,B,B,A,B,A]};

  // figure our coded number, 0 = white, 1 = black, L = long black
  var code = 'L0L';
  var left = family[ean[0]];
  for( var n = 0; n < 6; n++ ) {
    code += left[n][ean[n+1]]
  }
  code += '0L0L0';
  for( var n = 7; n < 13; n++ ) {
    code += groupC[ean[n]];
  }
  code += 'L0L';

  //console.log( ean + ": " + code );

  var height = 50;
  var canvas = elem;
  var showText = true;

  if( !canvas.getContext ) {
    return;
  }
  var ctx = canvas.getContext('2d');
  
  var width = code.length;
  var cwidth = (width < 100) ? 120 : width + 20;

  ctx.clearRect( 0, 0, canvas.width, canvas.height );
  canvas.style.height = canvas.height = height;
  canvas.style.width = canvas.width = cwidth;
  ctx.fillStyle = "rgb(255,255,255)";
  ctx.fillRect( 0, 0, canvas.width, canvas.height );

  var i, j;
  var curx = Math.round(cwidth/2-width/2);
  var cury = 10;
  var bheight = 40;

  if( showText ) {
    bheight -= 20;
  }

  for( i = 0; i < code.length; i++ ) {
    if( code[i] == 0 ) {
      ctx.fillStyle = "rgb(255,255,255)";
    } else {
      ctx.fillStyle = "rgb(0,0,0)";
    }
    if( code[i] == 'L' ) {
      ctx.fillRect( curx, cury, 1, bheight + 10);
    } else {
      ctx.fillRect( curx, cury, 1, bheight );
    }  
    curx++;
  }

  // text
  if( showText ) {
    ctx.font = '8pt Courier';
    ctx.fillStyle = "rgb(0,0,0)";
    ctx.fillText( ean[0], 6, bheight+cury+10 );
    curx = 17;
    for( i = 1; i < ean.length; i++ ) {
      ctx.fillText( ean[i], curx, bheight+cury+10 );
      curx += 7;
      if( i == 6 ) curx += 4;
    }
  }
}

function draw_code39( elem, code )
{
  var encodings = {
			'0':'bwbWBwBwb',
			'1':'BwbWbwbwB',
			'2':'bwBWbwbwB',
			'3':'BwBWbwbwb',
			'4':'bwbWBwbwB',
			'5':'BwbWBwbwb',
			'6':'bwBWBwbwb',
			'7':'bwbWbwBwB',
			'8':'BwbWbwBwb',
			'9':'bwBWbwBwb',
			'A':'BwbwbWbwB',
			'B':'bwBwbWbwB',
			'C':'BwBwbWbwb',
			'D':'bwbwBWbwB',
			'E':'BwbwBWbwb',
			'F':'bwBwBWbwb',
			'G':'bwbwbWBwB',
			'H':'BwbwbWBwb',
			'I':'bwBwbWBwb',
			'J':'bwbwBWBwb',
			'K':'BwbwbwbWB',
			'L':'bwBwbwbWB',
			'M':'BwBwbwbWb',
			'N':'bwbwBwbWB',
			'O':'BwbwBwbWb',
			'P':'bwBwBwbWb',
			'Q':'bwbwbwBWB',
			'R':'BwbwbwBWb',
			'S':'bwBwbwBWb',
			'T':'bwbwBwBWb',
			'U':'BWbwbwbwB',
      'V':'bWBwbwbwB',
			'W':'BWBwbwbwb',
			'X':'bWbwBwbwB',
			'Y':'BWbwBwbwb',
			'Z':'bWBwBwbwb',
			'-':'bWbwbwBwB',
			'.':'BWbwbwBwb',
			' ':'bWBwbwBwb',
			'$':'bWbWbWbwb',
			'/':'bWbWbwbWb',
			'+':'bWbwbWbWb',
			'%':'bwbWbWbWb',
			'*':'bWbwBwBwb' // B 66 W 87 b 98 w 119 |65 90| 		'0':'bwbWBwBwb',
  };

  var canvas = elem;
  var showText = true;

  code = '*' + code.toUpperCase() + "*";
  code = code.replace(/-/g,'');

  if( !canvas.getContext ) {
    return;
  }
  var ctx = canvas.getContext('2d');
  
  var width = code.length * 15 + code.length - 1;
  var cwidth = (width < 400) ? 400 : width + 40;

  var i, j;
  var curx = Math.round(cwidth/2-width/2);
  var cury = 10;
  var wwidth = 3; // wide bars are 3x width of narrow bars
  var bheight = (cwidth * 0.2);
  var height = bheight * 1.2;

  ctx.font = '8px Courier';
  ctx.clearRect( 0, 0, canvas.width, canvas.height );
  canvas.style.height = canvas.height = height;
  canvas.style.width = canvas.width = cwidth;
  ctx.fillStyle = "rgb(255,255,255)";
  ctx.fillRect( 0, 0, canvas.width, canvas.height );


  if( showText ) {
    bheight -= 20;
  }

  for( i = 0; i < code.length; i++ ) {
    var ch = encodings[code[i]];
    if( !ch ) {
      ch = encodings['-'];
    }

    for( j = 0; j < ch.length; j++ ) {
      if( j % 2 == 0 ) {
        ctx.fillStyle = "rgb(0,0,0)";
      } else {
        ctx.fillStyle = "rgb(255,255,255)";
      }

      if( ch.charCodeAt(j) < 91 ) {
        ctx.fillRect( curx, cury, wwidth, bheight );
        curx += wwidth;
      } else {
        ctx.fillRect( curx, cury, 1, bheight );
        curx += 1;
      }
      if( showText && (j == 4) && (typeof ctx.fillText == 'function') ) {
        ctx.fillStyle = "rgb(0,0,0)";
        ctx.fillText( code[i], curx-5, bheight+cury+10 );
      }
    }
    if( i != code.length -1 ) {
      ctx.fillStyle = "rgb(255,255,255)";
      ctx.fillRect( curx, cury, 1, bheight );
      curx += 1;
    }
  }
}

function draw_code128( elem, code )
{
   var CharSetA =  {
                ' ':0, '!':1, '"':2, '#':3, '$':4, '%':5, '&':6, "'":7,
                '(':8, ')':9, '*':10, '+':11, ',':12, '-':13, '.':14, '/':15,
                '0':16, '1':17, '2':18, '3':19, '4':20, '5':21, '6':22, '7':23,
                '8':24, '9':25, ':':26, ';':27, '<':28, '=':29, '>':30, '?':31,
                '@':32, 'A':33, 'B':34, 'C':35, 'D':36, 'E':37, 'F':38, 'G':39,
                'H':40, 'I':41, 'J':42, 'K':43, 'L':44, 'M':45, 'N':46, 'O':47,
                'P':48, 'Q':49, 'R':50, 'S':51, 'T':52, 'U':53, 'V':54, 'W':55,
                'X':56, 'Y':57, 'Z':58, '[':59, '\\':60, ']':61, '^':62, '_':63,
                '\x00':64, '\x01':65, '\x02':66, '\x03':67, '\x04':68, '\x05':69, '\x06':70, '\x07':71,
                '\x08':72, '\x09':73, '\x0A':74, '\x0B':75, '\x0C':76, '\x0D':77, '\x0E':78, '\x0F':79,
                '\x10':80, '\x11':81, '\x12':82, '\x13':83, '\x14':84, '\x15':85, '\x16':86, '\x17':87,
                '\x18':88, '\x19':89, '\x1A':90, '\x1B':91, '\x1C':92, '\x1D':93, '\x1E':94, '\x1F':95,
                'FNC3':96, 'FNC2':97, 'SHIFT':98, 'Code C':99, 'Code B':100, 'FNC4':101, 'FNC1':102, 'START A':103,
                'START B':104, 'START C':105, 'STOP':106
           }

   var CharSetB = {
                ' ':0, '!':1, '"':2, '#':3, '$':4, '%':5, '&':6, "'":7,
                '(':8, ')':9, '*':10, '+':11, ',':12, '-':13, '.':14, '/':15,
                '0':16, '1':17, '2':18, '3':19, '4':20, '5':21, '6':22, '7':23,
                '8':24, '9':25, ':':26, ';':27, '<':28, '=':29, '>':30, '?':31,
                '@':32, 'A':33, 'B':34, 'C':35, 'D':36, 'E':37, 'F':38, 'G':39,
                'H':40, 'I':41, 'J':42, 'K':43, 'L':44, 'M':45, 'N':46, 'O':47,
                'P':48, 'Q':49, 'R':50, 'S':51, 'T':52, 'U':53, 'V':54, 'W':55,
                'X':56, 'Y':57, 'Z':58, '[':59, '\\':60, ']':61, '^':62, '_':63,
                '' :64, 'a':65, 'b':66, 'c':67, 'd':68, 'e':69, 'f':70, 'g':71,
                'h':72, 'i':73, 'j':74, 'k':75, 'l':76, 'm':77, 'n':78, 'o':79,
                'p':80, 'q':81, 'r':82, 's':83, 't':84, 'u':85, 'v':86, 'w':87,
                'x':88, 'y':89, 'z':90, '{':91, '|':92, '}':93, '~':94, '\x7F':95,
                'FNC3':96, 'FNC2':97, 'SHIFT':98, 'Code C':99, 'FNC4':100, 'Code A':101, 'FNC1':102, 'START A':103,
                'START B':104, 'START C':105, 'STOP':106
           }

   var CharSetC = {
                '00':0, '01':1, '02':2, '03':3, '04':4, '05':5, '06':6, '07':7,
                '08':8, '09':9, '10':10, '11':11, '12':12, '13':13, '14':14, '15':15,
                '16':16, '17':17, '18':18, '19':19, '20':20, '21':21, '22':22, '23':23,
                '24':24, '25':25, '26':26, '27':27, '28':28, '29':29, '30':30, '31':31,
                '32':32, '33':33, '34':34, '35':35, '36':36, '37':37, '38':38, '39':39,
                '40':40, '41':41, '42':42, '43':43, '44':44, '45':45, '46':46, '47':47,
                '48':48, '49':49, '50':50, '51':51, '52':52, '53':53, '54':54, '55':55,
                '56':56, '57':57, '58':58, '59':59, '60':60, '61':61, '62':62, '63':63,
                '64':64, '65':65, '66':66, '67':67, '68':68, '69':69, '70':70, '71':71,
                '72':72, '73':73, '74':74, '75':75, '76':76, '77':77, '78':78, '79':79,
                '80':80, '81':81, '82':82, '83':83, '84':84, '85':85, '86':86, '87':87,
                '88':88, '89':89, '90':90, '91':91, '92':92, '93':93, '94':94, '95':95,
                '96':96, '97':97, '98':98, '99':99, 'Code B':100, 'Code A':101, 'FNC1':102, 'START A':103,
                'START B':104, 'START C':105, 'STOP':106
           }


   var ValueEncodings = {  
        0:'11011001100',  1:'11001101100',  2:'11001100110', 
        3:'10010011000',  4:'10010001100',  5:'10001001100',
        6:'10011001000',  7:'10011000100',  8:'10001100100',
        9:'11001001000', 10:'11001000100', 11:'11000100100',
        12:'10110011100', 13:'10011011100', 14:'10011001110',
        15:'10111001100', 16:'10011101100', 17:'10011100110',
        18:'11001110010', 19:'11001011100', 20:'11001001110',
        21:'11011100100', 22:'11001110100', 23:'11101101110',
        24:'11101001100', 25:'11100101100', 26:'11100100110',
        27:'11101100100', 28:'11100110100', 29:'11100110010',
        30:'11011011000', 31:'11011000110', 32:'11000110110',
        33:'10100011000', 34:'10001011000', 35:'10001000110',
        36:'10110001000', 37:'10001101000', 38:'10001100010',
        39:'11010001000', 40:'11000101000', 41:'11000100010',
        42:'10110111000', 43:'10110001110', 44:'10001101110',
        45:'10111011000', 46:'10111000110', 47:'10001110110',
        48:'11101110110', 49:'11010001110', 50:'11000101110',
        51:'11011101000', 52:'11011100010', 53:'11011101110',
        54:'11101011000', 55:'11101000110', 56:'11100010110',
        57:'11101101000', 58:'11101100010', 59:'11100011010',
        60:'11101111010', 61:'11001000010', 62:'11110001010',
        63:'10100110000', 64:'10100001100', 65:'10010110000',
        66:'10010000110', 67:'10000101100', 68:'10000100110',
        69:'10110010000', 70:'10110000100', 71:'10011010000',
        72:'10011000010', 73:'10000110100', 74:'10000110010',
        75:'11000010010', 76:'11001010000', 77:'11110111010',
        78:'11000010100', 79:'10001111010', 80:'10100111100',
        81:'10010111100', 82:'10010011110', 83:'10111100100',
        84:'10011110100', 85:'10011110010', 86:'11110100100',
        87:'11110010100', 88:'11110010010', 89:'11011011110',
        90:'11011110110', 91:'11110110110', 92:'10101111000',
        93:'10100011110', 94:'10001011110', 95:'10111101000',
        96:'10111100010', 97:'11110101000', 98:'11110100010',
        99:'10111011110',100:'10111101110',101:'11101011110',
        102:'11110101110',103:'11010000100',104:'11010010000',
        105:'11010011100',106:'11000111010' }

  // make a binary string of the code, 0 = white, 1 = black
  var bcode = '';
  var cset = 0;
  var pos = 0;
  var sum = 0;
  var skip = false;
  for( var i = 0; i < code.length; i++ ) {
    if( skip ) {
      skip = false;
      continue;
    }

    if((code.substr(i).length >= 4 && /^\d+$/.test(code.substr(i,4)) && cset != CharSetC) ||  
        (code.substr(i).length >= 2 && /^\d+$/.test(code.substr(i,2)) && cset == CharSetC) ) {
      if( cset != CharSetC ) {
        if( pos ) {
          bcode += ValueEncodings[cset['Code C']];
          sum += pos * cset['Code C'];
        } else {
          bcode = ValueEncodings[CharSetC['START C']];
          sum = CharSetC['START C'];
        }
        cset = CharSetC;
        pos += 1;
      }
    } else if ( code[i] in CharSetB && cset != CharSetB && !(code[i] in CharSetA && cset == CharSetA) ) {
      if( pos ) {
        bcode += ValueEncodings[cset['Code B']];
        sum += pos * cset['Code B'];
      } else {
        bcode = ValueEncodings[CharSetB['START B']];
        sum = CharSetB['START B'];
      }
      cset = CharSetB;
      pos += 1;
    } else if ( code[i] in CharSetA && cset != CharSetA && !(code[i] in CharSetB && cset == CharSetB) ) {
      if( pos ) {
        bcode += ValueEncodings[cset['Code A']];
        sum += pos * cset['Code A'];
      } else {
        bcode += ValueEncodings[CharSetA['START A']];
        sum = CharSetA['START A'];
      }
      cset = CharSetA;
      pos += 1;
    }
    if( cset == CharSetC ) {
      val = CharSetC[code.substr(i,2)];
      skip = true;
    } else {
      val = cset[code[i]];
    }
    sum += pos * val;
    bcode += ValueEncodings[val];
    pos += 1;
  }

  var checksum = sum % 103;
  bcode += ValueEncodings[checksum];
  bcode += ValueEncodings[cset['STOP']];
  bcode += '11';

  //console.log( bcode ); 

  var height = 50;
  var canvas = elem;
  var showText = true;

  if( !canvas.getContext ) {
    return;
  }
  var ctx = canvas.getContext('2d');
  
  var width = bcode.length;
  var cwidth = (width < 250) ? 250 : width + 20;

  ctx.clearRect( 0, 0, canvas.width, canvas.height );
  canvas.style.height = canvas.height = height;
  canvas.style.width = canvas.width = cwidth;
  ctx.fillStyle = "rgb(255,255,255)";
  ctx.fillRect( 0, 0, canvas.width, canvas.height );

  var i, j;
  var curx = Math.round(cwidth/2-width/2);
  var cury = 10;
  var bheight = 40;

  if( showText ) {
    bheight -= 20;
  }

  for( i = 0; i < bcode.length; i++ ) {
    if( bcode[i] == '0' ) {
      ctx.fillStyle = "rgb(255,255,255)";
    } else {
      ctx.fillStyle = "rgb(0,0,0)";
    }
    if( bcode[i] == 'L' ) {
      ctx.fillRect( curx, cury, 1, bheight + 10);
    } else {
      ctx.fillRect( curx, cury, 1, bheight );
    }  
    curx++;
  }

  // text
  if( showText ) {
    curx = Math.round(cwidth/2-width/2);
    for( i = 0; i < code.length; i++ ) {
      ctx.fillText( code[i], curx, bheight+cury+10 );
      curx += 7;
    }
  }

}

$(document).ready(function() {
  $("canvas.barcode").each(function(index, elem) {
    switch( $(elem).attr('type')) {
    case 'ean':
      draw_ean( elem, $(elem).attr('barcode'));
      break;
    case 'code39':
      draw_code39( elem, $(elem).attr('barcode'));
      break;
    case 'code128':
      draw_code128( elem, $(elem).attr('barcode'));
      break;
    default:
      if( $(elem).attr('barcode').length == 13 ) {
        draw_ean( elem, $(elem).attr('barcode'));
      }
    }
  });
});

</script>

<canvas class="barcode" barcode="<?=$order->order_no?>" type="code128"></canvas>

<table style="width: 80%">
  <tr>
   <th>Ship To</th>
   <th>Bill To</th>
  </tr>
  <tr>
  <td><pre><?=$order->shipto?></pre></td>
  <td><pre><?=$order->billto?></pre></td>
  </tr> 
</table>

<table style="width: 80%;">
<tr>
  <th>EAN</th>
  <th>Title</th>
  <th>Qty</th>
  <th>Price</th>
  <th>Total</th>
</tr>
<?php 
$count = 0;
$total = 0;
foreach( $order_lines as $line ): 
$total += ($line->qty * $line->price);
?>
<tr class="<?= ($count % 2 == 0 ? '' : 'odd')?>">
  <td><canvas class="barcode" barcode="<?=$line->ean?>" type="ean"></canvas></td>
  <td width="60%"><?=$line->title?></td>
  <td><?=$line->qty?></td>
  <td><?=$line->price?></td>
  <td><?=$line->qty*$line->price?></td>
</tr>
<?php 
$count++;  
endforeach; ?>
<tr style="background-color: #ff9">
  <td colspan="2"/>
  <td align="right"><b>Sub-Total</b><td>
  <td><b><?=$total?></b></td>
</tr>
</table>


<!--
<canvas class="barcode" barcode="1111111111116"></canvas>

<canvas class="barcode" barcode="9781551050966"></canvas>

<canvas class="barcode" barcode="1234567890128"></canvas>
-->

