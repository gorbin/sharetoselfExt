//
//  Action.js
//  Share to Self
//
//  Created by mac13 on 21.10.16.
//  Copyright © 2016 mac13. All rights reserved.
//

var Action = function() {};

Action.prototype = {
    
run: function(arguments) {
    // Here, you can run code that modifies the document and/or prepares
    // things to pass to your action's native code.
    
    // We will not modify anything, but will pass the body's background
    // style to the native code.
    
    arguments.completionFunction({ "URL": document.URL,
                                 "TITLE" : document.title })
    
},
    
finalize: function(arguments) {
    // This method is run after the native code completes.
    
    // We'll see if the native code has passed us a new background style,
    // and set it on the body.
    
    //        var newBackgroundColor = arguments["newBackgroundColor"]
    //        if (newBackgroundColor) {
    //            // We'll set document.body.style.background, to override any
    //            // existing background.
    //            document.body.style.background = newBackgroundColor
    //        } else {
    //            // If nothing's been returned to us, we'll set the background to
    //            // blue.
    //            document.body.style.background= "blue"
    //        }
    
    var message = arguments["email"];
    
    if (message) {
        alert(message);
    }
}
    
};

var ExtensionPreprocessingJS = new Action
