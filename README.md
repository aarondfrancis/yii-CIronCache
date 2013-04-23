yii-CIronCache
==============

An IronCache implementation for Yii, mostly for use on Heroku

Based heavily on John Eskilsson's implementation of [yiiron](https://github.com/br0sk/yiiron). I stripped out the cache part because I didn't want all the rest of it.

I set mine up as 'iron' instead of 'cache', because I only want to use the IronCache for a few things and the FileCache for the rest.

To learn more, read the blog post here: [http://aaronfrancis.com/blog/2013/4/9/some-thoughts-about-hosting-yii-on-heroku](http://aaronfrancis.com/blog/2013/4/9/some-thoughts-about-hosting-yii-on-heroku)


````
'components'=>array(
	'cache'=>array(
		'class' => 'system.caching.CFileCache'
	),
	'iron'=>array(
		'class'=>'CIronCache',
		'cacheName'=>'testCache',
		'project_id'=>'525dd788ed3d7669c70050f0',
		'token'=>'OP2NQJ228xxseEXU-mIoan5udWQ'
	),
)
````

Or if on heroku
````
'components'=>array(
	'cache'=>array(
		'class' => 'system.caching.CFileCache'
	),
	'iron'=>array(
		'class'=>'CIronCache',
		'cacheName'=>'testCache',
		'project_id'=>getenv('IRON_CACHE_PROJECT_ID'),
		'token'=>getenv('IRON_CACHE_TOKEN')
	),
)
````

Use at your own risk, I haven't tested it much. 