package 
{
	import flash.events.Event;
	import org.papervision3d.view.BasicView;
	import org.papervision3d.materials.special.LineMaterial;
	import org.papervision3d.objects.primitives.Sphere;
	import org.papervision3d.materials.ColorMaterial;
	
	import net.doublebow.objects.special.Trident3D;
	import org.papervision3d.objects.DisplayObject3D;
	
	public class Main extends BasicView 
	{
		private var rotX:Number = 0.2;
		private var rotY:Number = 0.2;
		private var camPitch:Number = 110;
		private var camYaw:Number = 270;
		private var easeOut:Number = 0.1;
		
		private var sphere:Sphere;
		private var trid:Trident3D;
		
		public function Main() {
			super();
			viewport.autoScaleToStage = false;
			viewport.viewportHeight = 340;
			viewport.viewportWidth = 480;
			//viewport.
			viewport.interactive = true;
			
			this._camera.z = -600;
			this._camera.fov = 40;
			
			// Create and add trident
			trid = new Trident3D();
			trid.y = -50;
			scene.addChild(trid);
			
			//Create a skeleton sphere
			sphere = new Sphere(new ColorMaterial(0xff7777), 30);
			scene.addChild(sphere);
						
			sphere.z = 60;
			sphere.y = 30;
			sphere.x = 60;
			
			startRendering();
		}
				
		override protected function onRenderTick(event:Event = null): void {
			super.onRenderTick(event);
		
			var xDist:Number = mouseX - stage.stageWidth * 0.9;
			var yDist:Number = mouseY - stage.stageHeight * 0.9;
			camPitch += ((yDist * rotX) - camPitch + 90) * easeOut;
			camYaw += ((xDist * rotY) - camYaw + 270) * easeOut;
			camera.orbit(camPitch, camYaw);
		}
	}
}