package  {
	
	import flash.events.ProgressEvent;
	import flash.display.DisplayObjectContainer;
	import flash.display.DisplayObject;
	
	public class PercentProgress implements IProgressDisplay {
		
		private var pbar:DisplayObjectContainer;
		private var pborder:DisplayObject;
		private var con:DisplayObjectContainer;
		private var removed:Boolean = false;

		public function PercentProgress(conatiner:DisplayObjectContainer, initPosX:Number = 240, initPosY:Number = 170) {
			this.con = conatiner;
			this.pbar = new ProgressBar() as DisplayObjectContainer;
			this.pborder = new BarBorder() as DisplayObject;
			
			pborder.x = initPosX;
			pbar.x = initPosX;
			pborder.y = initPosY;
			pbar.y = initPosY;
			
			con.addChild(pborder);
			
			for(var i:int = 0; i < pbar.numChildren; i++) {
				pbar.getChildAt(i).visible = false;
			}
			con.addChild(pbar);
		}
		
		public function showProgress(event:ProgressEvent): void {
			var percLoaded:Number = Math.round(event.bytesLoaded / event.bytesTotal * 100);
			
			if(percLoaded >= 100 && !removed) {
				con.removeChild(pbar);
				con.removeChild(pborder);
				removed = true;
			} else {
				setBarProgress(percLoaded);
			}
		}
		
		private function setBarProgress(percent:int) {
			for(var i:int = 0; i < pbar.numChildren; i++) {
				if(percent > i * 10 && !pbar.getChildAt(i).visible) {
					pbar.getChildAt(i).visible = true;
				}
			}
		}
	}
}
