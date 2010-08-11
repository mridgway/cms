YUI().add('page', function(Y) {
   function Page(config) {
       Page.superclass.constructor.apply(this, arguments);
   };
   
   // applies static properties
   Y.mix(Page, {
       NAME : 'page',
       PAGE : null
   }, true);

   Page.NAME = "page";
   
   // set page attributes
   Page.ATTRS = {
       id : {
           value: 0,
           validator: Y.Lang.isNumber
       },
       
       locations : {
           value: {},
           validator: Y.Lang.isObject
       },
       
       source : {
           value : null,
           validator: Y.Lang.isString,
           getter: function () {
               return '/direct/page/get-info?id=' + this.get('id');
           }
       },

       actions : {
           value : []
       },

       sortables : {
           value : []
       }
   };
   

   Y.extend(Page, Y.Base, {
       // prototype
       
       _dataSource : null,
       
       /**
        * 
        */
       initializer : function(config) {
           this._getId();
           
           // page object is no longer fetched
           // this._setDataSource();
           // this._dataSource.on('response', this._load, this);
           // this._dataSource.sendRequest();
           
           this._load();
           
           Page.PAGE = this;
       },
       
       getInstance : function () {
           return Page.PAGE;
       },
       
       _getId : function () {
           this.set('id', parseInt(
               Y.one('body').getAttribute('id').replace(/\D/gi, '')
           ));
       },
       
       // _setDataSource : function () {
       //     if (!Y.Lang.isNull(this._dataSource)) {
       //         return;
       //     };
       //     
       //     this._dataSource = new Y.DataSource.IO({
       //         source:this.get('source')
       //     });
       // 
       //     this._dataSource.plug(Y.Plugin.DataSourceJSONSchema, {
       //         schema: {
       //             metaFields: {code: 'code'},
       //             resultListLocator: "data",
       //             resultFields: ['id', 'locations', 'actions']
       //         }
       //     });
       // },
       
       _load : function(e) {
           // load is no longer called a callback
           // var page = e.response.results[0];
           var page = PAGE_INFO;

           this.set('locations', page.locations);
           this.set('actions', page.actions);
           this._setup();
       },
       
       _setup : function () {
           // setup blocks
           var locations = this.get('locations');
           for(x in locations) {
               var q = new Y.AsyncQueue();
               q.add({
                   fn : Y.bind(function (x) {
                            this._setupLocation(locations[x]);
                        }, this, x),
                   timeout: -1
               },
               {
                   fn : Y.bind(function (x) {
                        this._setupSortable(locations[x]);
                    }, this, x),
                   timeout: -1
               });
               q.run();
           }
       },
       
       _setupLocation : function (location) {
           var blocks = location.blocks,
                self = this,
                block = null;
            
            Y.use('block', '*', function (Y) {
                for(x in blocks) {
                    var node = Y.one('#block-' + blocks[x].id + '-wrapper');
                    var block = new Y.Block({
                        contentBox: node._node,
                        id: blocks[x].id,
                        page: self
                    });

                    block.render();
                    self._setupBlock(blocks[x], block);
                }
            });

            var addBlockButton = Y.Node.create(
                    '<div class="page-block-new">'
                        + '<a href="#">Add New Block</a>'
                    + '</div>');

            addBlockButton.appendChild(Y.Node.create(
                    '<ul class="block-types">'
                        + '<li><a href="#">Standard</a></li>'
                        + '<li><a href="#">Shared</a></li>'
                        + '<li><a href="#">Dynamic</a></li>'
                    + '</ul>').toggleClass('hidden'));

            addBlockButton.one('a').on('click', function () {
                this.toggleClass('hidden');
                this.next().toggleClass('hidden');
                return false;
            });

            Y.one('#' + location.sysname).appendChild(addBlockButton);

       },
       
       _setupBlock : function (blockData, block) {
           var node = Y.one('#block-' + blockData.id + '-wrapper');
           if (Y.Lang.isObject(blockData.actions)) {
                  // add actions to block
				function ReferenceCounter(stack) {
					this.executionStack = stack || [];
					this.count = 0;
				}

				ReferenceCounter.prototype.retain = function () {
					this.count++;
				};

				ReferenceCounter.prototype.release = function () {
					this.count--;
					if (this.count == 0) {
						for(x in this.executionStack) {
							this.executionStack[x]();
						}
					}
				};

				ReferenceCounter.prototype.pushToStack = function (func) {
					this.executionStack.push(func);
				};

				var refCounter = new ReferenceCounter();
				for(x in blockData.actions) {
					var loadFunc = function () {
                        var action = blockData.actions[x];

                        refCounter.pushToStack(function () {
                            Y.use('widget', 'block', action.name, '*',  function (Y) {
                                block.plug(Y.Plugin[action.plugin], {
                                    action: action
                                });
                            });
                        });
                        refCounter.retain();
					};
					loadFunc();
				}

				for(x in blockData.actions) {
                    refCounter.release()
				}
          }
       },

       _setupSortable : function (location) {
           var select = new Y.Sortable({
               container: '#' + location.sysname,
               nodes: '.yui3-block',
               opacity: 0.5,
               moveType: 'insert',
               handles: ['.yui3-block-move']
           });
           select.delegate.dd.plug(Y.Plugin.DDConstrained, {
               constrain2node: 'body'
           });
           select.delegate.dd.set('startCentered', true);
           select.delegate.dd.proxy.set('cloneNode', false);
           select.delegate.dd.proxy.set('borderStyle', '1px solid #555');
           select.delegate.dd.on('drag:start', function(e) {
               if(!this.activeDrag) {
                   e.cancelBubble = true;
               }
                e.target.get('dragNode').setStyle('width', '50px');
                e.target.get('dragNode').setStyle('height', '50px');
                e.target.get('dragNode').setStyle('backgroundColor', '#666');
                e.target.get('dragNode').setStyle('opacity', .5);
           });
           select.delegate.dd.on('drag:end', this._dragEnd, this);
           var sortables = this.get('sortables');
           for (x in sortables) {
               sortables[x].join(select, 'full');
           }
           this.get('sortables').push(select);
       },

       _dragEnd : function (e) {
            // compute block orders
            var locations = this.get('locations');
            var newLocations = this._getLocationsFromArray(this._getBlockLocationsFromDOM());
            if (Y.JSON.stringify(locations) != Y.JSON.stringify(newLocations)) {
                this._save(newLocations);
            }
       },

       _getBlockLocationsFromDOM : function () {
            var locations = this.get('locations');
            var newLocations = new Array();
            for (i in locations) {
                newLocations[locations[i].sysname] = new Array();
                var blockNodes = Y.one('#' + locations[i].sysname).all('.block');
                blockNodes.each(function () {
                    var blockId = this.get('id').split('-').reverse()[0];
                    newLocations[locations[i].sysname][blockNodes.indexOf(this)] = parseInt(blockId);
                });
            }
            return newLocations;
       },

       _getLocationsFromArray : function (newLocations) {
            var locations = Y.clone(this.get('locations'));
            var blocks = new Array();
            for (i in locations) {
                for(x in locations[i].blocks) {
                    var id = locations[i].blocks[x].id;
                    blocks[id] = locations[i].blocks[x];

                }
            }

            for (i in locations) {
                var newLocation = newLocations[locations[i].sysname];
                locations[i].blocks = new Array();
                for (x in newLocation) {
                    locations[i].blocks[x] = blocks[newLocation[x]];
                }
            }

            return locations;
       },
       
       //@todo check to see if changes were made
       _save : function (locations) {
            var saveAction = this.get('actions').blockRearrange;

            var postbackData = new Object;
            postbackData.data = new Array();
            postbackData.data[0] = new Object;
            postbackData.data[0].id = this.get('id');
            postbackData.data[0].locations = locations;
            var ds = new Y.DataSource.IO({
                source: saveAction.postback
            });
            ds.plug(Y.Plugin.DataSourceJSONSchema, {
                schema: {
                    metaFields: {code: 'code' },
                    resultListLocator: "data",
                    resultFields: ['id', 'locations', 'actions']
                }
            });
            ds.on('response', function (e) {
                if (e.response.meta.code.id > 0) {
                    alert('There was an error processing your move request. Please refresh the page.');
                    return;
                }
                this.set('locations', locations);
            }, this);
            ds.sendRequest({
                cfg : {
                    method : 'POST',
                    data : 'page=' + Y.JSON.stringify(postbackData)
                }
            });
       }
   });
   
   Y.Page = Page;
}, '3.1.0');
