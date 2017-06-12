package  {
	
	import flash.display.Loader;
	import flash.events.Event;
	import flash.events.ProgressEvent;
	import flash.display.DisplayObjectContainer;
	import flash.net.URLRequest;
	import flash.display.MovieClip;
	
	public class FlashWebLoader extends Loader {
		
		private var container:DisplayObjectContainer;
		private var progressDisplayer:IProgressDisplay;
		private var obj:URLRequest;
		
		public static function Load(container:DisplayObjectContainer, progressDisplayer:IProgressDisplay, objectToLoad:URLRequest): FlashWebLoader {
			return new FlashWebLoader(container, progressDisplayer, objectToLoad);
		}

		public function FlashWebLoader(container:DisplayObjectContainer, progressDisplayer:IProgressDisplay, objectToLoad:URLRequest) {
			super();
			this.container = container;
			this.progressDisplayer = progressDisplayer;
			this.obj = objectToLoad;
			this.container.loaderInfo.addEventListener(Event.COMPLETE, loadScene);
		}
		
		private function loadScene(e:Event): void {
			container.loaderInfo.removeEventListener(Event.COMPLETE, loadScene);
			contentLoaderInfo.addEventListener(Event.COMPLETE, initScene);
			contentLoaderInfo.addEventListener(ProgressEvent.PROGRESS, progressDisplayer.showProgress);
			this.load(obj);
		}
		
		private function initScene(e:Event): void {
			contentLoaderInfo.removeEventListener(Event.COMPLETE, initScene);
			contentLoaderInfo.removeEventListener(ProgressEvent.PROGRESS, progressDisplayer.showProgress);
			this.container.addChild(this.content);
		}
	}
}
