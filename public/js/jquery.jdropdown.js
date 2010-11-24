/* jQuery dropdown multi-level menu function
 *
 * @author: J Knight <jim AT j2mfk DOT com>
 *
 * Example
 *
 * <div id="nav">
 * <ul class="dropdown">
 *   <li><a href="...">Foo<a>
 *     <ul class="submenu">
 *       <li><a href="...">bar</a></li>
 *       ...
 *     </ul>
 *   </li>
 * </ul>
 * </div>
 * $('#nav').dropDown({});
 *
 * Copyright 2010 J Knight (http://j2mfk.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 	http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
(function($) {
  // default values
  $.dropDown = {
    defaults: {
      effect: 'slide',
      speed: 'slow'
    }
  };
  // the function
  $.fn.extend({
    dropDown: function( config ) {
      // options
      var opts = $.extend({}, $.dropDown.defaults, config);
      
      // Only shows drop down trigger when js is enabled
      // (Adds empty span tag after ul.submenu*)
      //$("ul.submenu").parent().append("<span></span>");
      // only put the trigger on the top level items
      $("ul.dropdown > li > ul.submenu").parent().append("<span></span>");

      //When trigger is clicked...
      $("ul.dropdown li span").click(function() {

          // show the submenu
          switch( opts.effect ) {
            case 'slide':
              $(this).parent().find("> ul.submenu").slideDown(opts.speed);
              break;
            case 'fade':
              $(this).parent().find("> ul.submenu").fadeIn(opts.speed);
              break;
            default:
              $(this).parent().find("> ul.submenu").show(opts.speed);
              break;
          }

          // hover
          $(this).parent().hover(
            function() {
              // do nothing
            },
            function(){
              //When the mouse hovers out of the submenu, make it go away
              switch( opts.effect ) {
                case 'slide':
                  $(this).parent().find("ul.submenu").slideUp(opts.speed);
                  break;
                case 'fade':
                  $(this).parent().find("ul.submenu").fadeOut(opts.speed);
                  break;
                default:
                  $(this).parent().find("ul.submenu").hide(opts.speed);
                  break;
              }
            }
          );

        // hover events for the trigger, changes the image view
        }).hover(
          function() { // on hover
            $(this).addClass("subhover"); //On hover over, add class "subhover"
          },
          function(){	// on hover out
            $(this).removeClass("subhover"); //On hover out, remove class "subhover"
          }
      );

      // hover for sub submenus
      $('ul.dropdown > li > ul.submenu > li').hover(
        function() { // on hover
          $(this).find("ul.submenu").css('left', 40);
          switch( opts.effect ) {
            case 'slide':
              $(this).find("ul.submenu").slideDown(opts.speed);
              break;
            case 'fade':
              $(this).find("ul.submenu").fadeIn(opts.speed);
              break;
            default:
              $(this).find("ul.submenu").show(opts.speed);
              break;
          }
        },
        function() { // on hover out
          switch( opts.effect ) {
            case 'slide':
              $(this).find("ul.submenu").slideUp(opts.speed);
              break;
            case 'fade':
              $(this).find("ul.submenu").fadeOut(opts.speed);
              break;
            default:
              $(this).find("ul.submenu").hide(opts.speed);
              break;
          }
      });
    }
  });
})(jQuery);
