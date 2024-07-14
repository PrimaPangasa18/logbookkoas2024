/*
 * File:				js-core.js
 * CVS:				  $Id$
 * Description: JS Core library
 * Author:			Allan Jardine
 * Created:		  Sat May 19 21:56:46 GMT 2007
 * Modified:		$Date$ by $Author$
 * Language:		JavaScript
 * Project:		  JSCore
 * 
 * Copyright 2007 Allan Jardine. All rights reserved.
 *
 * JSCore will provide functions for:
 *  Logging
 *  Event handling
 *  DOM ready
 * 
 */

/*
 * Function: JSCore
 * Purpose:	JSCore constructor
 * Returns:	-
 * Inputs:	 -
 * Notes:
 *
 */
JSCore = function(	) {
	this.fnInit( );
};

/*
 * Function: JSCore
 * Purpose:	JSCore class
 * Returns:	-
 * Inputs:	 -
 * Notes:
 *
 */
JSCore.prototype = {
	fnInit: function( )
	{
		//
		// onContentAvailable variables init
		//
		this.aStack = new Array();
		this.oInterval;
		this.bLoaded; // has DOMContentLoaded or window.onload fired
		
		//
		// Events varaibles init
		//
		this.aEventCache = new Array ();
		
		//
		// Logging init
		//
		this._fnLogInit( );
	},
	
	
	/*
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * onAvailable functions
	 */
	
	
	
	
	
	
	/*
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Event handling functions
	 *
	 *
	 * Function: JSCore.addListener
	 * Purpose:  Attach events to the selected nodes/IDs with scope and event 
	 *   correction. Drop in replacement for YUI addListener function
	 * Returns:  -
	 * Inputs:
	 *   object:oParamters {
	 *     mixed:mElement - nodes which are to have the event attached
	 *       - node list - list of nodes
	 *       - node - single node
	 *       - string - ID of node to be considered
	 *     string:sType - the event action trigger (ie click, mouseover etc)
	 *     function:fnCallback - the call back function
	 *     object:oObj - optional - object to be passed to the callback fn
	 *     mixed:mScope - optional - over ride of scope
	 *     	 - true (Boolean) - make the oObj object the scope of the exec fn
	 *     	 - string - object string to be used as the executation scope
	 *     string:sLabel - id label for the event - default ''
	 *   }
	 * Notes:    This function is very similar in the way it behaves to the YUI 
	 *   YAHOO.util.Event.addListener function and should function identically 
	 *   (without the overhead of the YUI library). Automatic scope correction  
	 *   and event correction is made, such that the scope of the callback is 
	 *   the object that the user clicked (unless overridden) and that the 
	 *   callback function has access to the 'event' object without jumping 
	 *   through hoops (IE). Scope can be over ridden such that the execution  
	 *   scope will be whatever the developer requires, which is particularly 
	 *   useful for namespaced Javascript.
	 * 
	 */
	addListener: function( oParameters )
	{
		var mScope;
		var fnWrapped;
		
		typeof oParameters.sLabel != 'undefined' ? null : oParameters.sLabel='';
		typeof oParameters.oObj != 'undefined' ? null : oParameters.oObj=null;
		typeof oParameters.mOverrideScope != 'undefined' ? 
			null : oParameters.mOverrideScope=null;
		
		/*
		 * Scope correction
		 */
		if ( oParameters.mScope )
		{
			// Override scope to be the object passed in
			if ( oParameters.mScope === true )
			{
				mScope = oParameters.oObj;
			}
			// Override scope to be the scope given
			else
			{
				mScope = oParameters.mScope;
			}
		}
		// Correct the scope to the element in consideration
		else
		{
			mScope = oParameters.mElement;
		}
		
		/*
		 * Wrap the callback function in a wrapper function to allow scope
		 * alteration and passing of oObj to it
		 */
		fnWrapped = function ( e )
		{
			// Make sure the callback object is available when event occours
			if ( !jsCore )
			{
				return 0;
			}
			else
			{
				oParameters.fnCallback.call( mScope, jsCore._getEvent(e), 
					oParameters.oObj );
			}
			return false;
		};
		
		/*
		 * Add the event to the event elements list
		 */
		if ( typeof(oParameters.mElement) == 'string' )
		{
			var nElement = document.getElementById( oParameters.mElement );
			jsCore._addEvent( { nNode: nElement,
				                  sType: oParameters.sType,
				             fnCallback: oParameters.fnCallback,
				      fnCallbackWrapped: fnWrapped,
				                 sLabel: oParameters.sLabel } );
		}
		else
		{
			// If single node
			if ( oParameters.mElement )
			{
				if ( oParameters.mElement.nodeName || oParameters.mElement == window )
				{
						jsCore._addEvent( { nNode: oParameters.mElement,
							                  sType: oParameters.sType,
							             fnCallback: oParameters.fnCallback,
							      fnCallbackWrapped: fnWrapped,
				                       sLabel: oParameters.sLabel } );
				}
				// If node list
				else
				{
					for ( var i=0 ; i<oParameters.mElement.length ; i++ )
					{
						jsCore._addEvent( { nNode: oParameters.mElement[i],
							                  sType: oParameters.sType,
							             fnCallback: oParameters.fnCallback,
							      fnCallbackWrapped: fnWrapped,
				                       sLabel: oParameters.sLabel } );
					}
				}
			}
		}
	},
	
	
	/*
	 * Function: JSCore._addEvent
	 * Purpose:  Add a single event to the single element required
	 * Returns:  -
	 * Inputs:   
	 *   obj {
	 *     node:nNode - the element to have the event attached to
	 *     string:sType - the event type
	 *     function:fnCallback - the callback function
	 *     function:fnCallbackWrapped - the callback function - wrapped
	 *     string:sLabel - event label
	 *   }
	 * Notes:    This function will add the function material to the EventCache
	 *   so it can be removed later on
	 * 
	 */
	_addEvent: function( obj )
	{
		this.aEventCache[this.aEventCache.length++] = {
			    'nNode': obj.nNode,
		      'sType': obj.sType,
			'fnWrapped': obj.fnCallbackWrapped,
			       'fn': obj.fnCallback,
			   'sLabel': obj.sLabel };
		
		if ( obj.nNode.addEventListener )
		{
			obj.nNode.addEventListener( obj.sType,	obj.fnCallbackWrapped, false );
		}
		else if ( obj.nNode.attachEvent )
		{
			obj.nNode.attachEvent( "on"+obj.sType, obj.fnCallbackWrapped );
		}
	},
	
	
	
	/*
	 * Function: JSCore.removeListener
	 * Purpose:  Remove listeners from a node, a list of nodes or a DOM ID
	 * Returns:  -
	 * Inputs:   
	 *   oParameters {
	 *     mElement - mixed - nodes which are to have the event attached
	 *       node list - list of nodes
	 *       node - single node
	 *       string - ID of node to be considered
	 *     sType - string - the event type
	 *     fnCallback - fn - the callback function
	 *     string:sLabel - label set, if set all events of that label will be
	 *       removed
	 *   }
	 *
	 */
	removeListener: function( oParameters )
	{
		this.aiDelete = new Array();
		
		// Dealing with an event label
		if ( typeof oParameters.sLabel == 'string' )
		{
			for ( var i=0 ; i<this.aEventCache.length ; i++ )
			{
				if ( this.aEventCache[i].sLabel == oParameters.sLabel )
				{
					jsCore._removeEvent( { nNode: this.aEventCache[i].nNode, 
					                       sType: this.aEventCache[i].sType,
					                          fn: this.aEventCache[i].fn } );
				}
			}
		}
		// Dealing with an DOM ID
		else if ( typeof oParameters.mElement == 'string' )
		{
			nElement = document.getElementById( oParameters.mElement );
			jsCore._removeEvent( { nNode: nElement, 
				                     sType: oParameters.sType,
				                        fn: oParameters.fnCallback } );
		}
		// Otherwise must be a node or node list
		else
		{
			// If single node
			if ( !oParameters.mElement.length )
			{
				jsCore._removeEvent( { nNode: oParameters.mElement, 
					                     sType: oParameters.sType,
					                        fn: oParameters.fnCallback } );
			}
			// If node list
			else
			{
				for ( var i=0 ; i<oParameters.mElement.length ; i++ )
				{
					jsCore._removeEvent( { nNode: oParameters.mElement[i], 
						                     sType: oParameters.sType,
						                        fn: oParameters.fnCallback } );
				}
			}
		}
		
		for ( var i=this.aiDelete.length-1 ; i>= 0 ; i-- )
		{
			this.aEventCache.splice( this.aiDelete[i], 1 );
		}
	},
	
	
	/*
	 * Function: JSCore._removeEvent
	 * Purpose:  Remove a single event listener from a node
	 * Returns:  -
	 * Inputs:   
	 *   obj {
	 *     nNode - node - node to have event listener removed
	 *     sType - string - the event type
	 *     fn - fn - the callback function
	 *   }
	 * Notes:    This function will search the event cache to find the wrapped 
	 *   function which must be removed.
	 * 
	 */
	_removeEvent: function( obj )
	{
		// Search the event cache
		for ( var i=0 ; i<this.aEventCache.length ; i++ )
		{
			if ( obj.nNode == this.aEventCache[i].nNode && 
				   obj.sType == this.aEventCache[i].sType &&
				      obj.fn == this.aEventCache[i].fn )
			{
				if ( obj.nNode.removeEventListener )
				{
					obj.nNode.removeEventListener( obj.sType, 
						this.aEventCache[i].fnWrapped, false );
				}
				else if ( obj.nNode.detachEvent )
				{
					obj.nNode.detachEvent( "on"+obj.sType, this.aEventCache[i].fnWrapped );
				}
				
				break;
			}
		}
		
		this.aiDelete[this.aiDelete.length++] = i;
		// Remove the event form the cache
		//this.aEventCache.splice( i, 1 );
	},
	
	/*
	 * Function: JSCore._getEvent
	 * Purpose:  Correct for IE's handling of events - get the current event
	 * Returns:  
	 *   ev - event
	 * Inputs:   
	 *   e - event
	 * 
	 */
	_getEvent: function( e )
	{
		return e || window.event;
	},
	
	
	/*
	 * Function: getElementFromEvent
	 * Purpose:  Get a node from an event handler in a cross platform way
	 * Returns:  nTarget - the target node from the event
	 * Inputs:   e - event handler from the mouse event
	 * Errors:   -
	 * 
	 * Notes:
	 * 
	 * 
	 */
	getElementFromEvent: function( e ) {
		var nTarget;
			
		if (e.target)
			nTarget = e.target;
		else if (e.srcElement)
			nTarget = e.srcElement;
	
		if (nTarget.nodeType == 3) // defeat Safari bug
			nTarget = nTarget.parentNode;
		
		return ( nTarget );
	},
	
	/*
	 * End event handlers
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *
	 *
	 *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Logging functions
	 */
	
	/* 
	 * Variable: _log_aDatabase
	 * Purpose:	
	 * Type:		 string array
	 * Scope:		Private
	 * 
	 */
	_log_aDatabase: new Array( ),
	
	/*
	 * Function: fnLog
	 * Purpose:	 Log information
	 * Returns:	 -
	 * Inputs:	 string:sMessage - the message to be displayed
	 *           int:iLevel - logging level
	 * Notes:		 -
	 */
	fnLog: function( sMessage, iLevel )
	{
		
	},
	
	/*
	 * Function: _fnLogInit
	 * Purpose:	 Initalised the logger
	 * Returns:	 -
	 * Inputs:	 -
	 * Notes:		 -
	 */
	_fnLogInit: function( )
	{
		// Capture the required keystroke for showing the logger
		if( document.addEventListener )
		{
			document.addEventListener( 'keydown', this._fnLogShowHide, true );
		}
		else if ( document.attachEvent )
		{
			document.attachEvent( "on"+ 'keydown', this._fnLogShowHide );
		}
	},
	
	/*
	 * Function: _fnLogShowHide
	 * Purpose:	 Show or hide the logging window
	 * Returns:	 -
	 * Inputs:	 -
	 * Notes:		 -
	 */
	_fnLogShowHide: function( eEvent )
	{
		eEvent = eEvent || window.event;        // gets the event in ie or ns
	  var kCode = eEvent.keyCode || eEvent.which; // gets the keycode in ie or ns
		var keyString = String.fromCharCode(kCode).toLowerCase();
		
		// ctrl+shift+l (fucking firefox)
		if ( eEvent.ctrlKey && eEvent.shiftKey && keyString == 'l' )
		{
			alert( 'target key store detected '+eEvent.shiftKey +' '+ keyString +' '+kCode );
		}
	}
	
	/*
	 * End logging functions functions
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 */
};


/* 
 * Variable: jsCore
 * Type:		 JSCore
 * Scope:		Global
 * 
 */
var jsCore = new JSCore( );
jsCore.bAvailable = true;