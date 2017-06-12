package {
	
	import org.papervision3d.core.geom.Lines3D;
	import org.papervision3d.core.geom.renderables.Line3D;
	import org.papervision3d.core.geom.renderables.Vertex3D;
	import org.papervision3d.objects.DisplayObject3D;
	import org.papervision3d.core.proto.GeometryObject3D;
	import org.papervision3d.materials.special.LineMaterial;
	
	/**
	 * @author Piotr Kubica
	 * http://doublebow.net
	 */
	public final class Trident3D extends DisplayObject3D {
		
		private var arrX:Lines3D;
		private var arrY:Lines3D;
		private var arrZ:Lines3D;
		
		private var thickness:Number;
		private var tSize:Number;
				
		private static var SHAFT_RATIO:int = 10;
		private var ahWidth:Number;
		private var ahHeight:Number;
		
		public function Trident3D(materialX:LineMaterial = null, materialY:LineMaterial = null, 
								  materialZ:LineMaterial = null, size:Number = 200, lineThickness:Number = 1, 
								  name:String = null) {
			
			super(name, new GeometryObject3D);
			thickness = lineThickness;
			tSize = size;
			ahWidth = tSize / (2 * SHAFT_RATIO);
			ahHeight = tSize / SHAFT_RATIO;
			
			materialX = materialX ? materialX : new LineMaterial(0xFF0000);
			materialY = materialY ? materialY : new LineMaterial(0x00FF00);
			materialZ = materialZ ? materialZ : new LineMaterial(0x0000FF);
			
			arrX = drawArrowX(materialX);
			arrY = drawArrowY(materialY);
			arrZ = drawArrowZ(materialZ);
			
			this.addChild(arrX);
			this.addChild(arrY);
			this.addChild(arrZ);
		}
		
		private function drawArrowZ(material:LineMaterial): Lines3D {
			var arr:Lines3D = new Lines3D();
			
			// z-axis arrow shaft
			var v0:Vertex3D = new Vertex3D();
			var vz:Vertex3D = new Vertex3D(0, 0, tSize);
			var zShaft:Line3D = new Line3D(arr, material, thickness, v0, vz);
			arr.addLine(zShaft);
			
			// z-axis arrowhead 
			var za:Array = new Array(
				new Vertex3D(0,        -ahWidth, tSize - ahHeight),
				new Vertex3D(-ahWidth, 0,        tSize - ahHeight),
				new Vertex3D(0,        ahWidth,  tSize - ahHeight),
				new Vertex3D(ahWidth,  0,        tSize - ahHeight)
			);
			
			drawArrowHead(arr, vz, za, material);
			return arr;
		}
		
		private function drawArrowY(material:LineMaterial): Lines3D {
			var arr:Lines3D = new Lines3D();
			
			// y-axis arrow shaft
			var v0:Vertex3D = new Vertex3D();
			var vy:Vertex3D = new Vertex3D(0, tSize, 0);
			var yShaft:Line3D = new Line3D(arr, material, thickness, v0, vy);
			arr.addLine(yShaft);
			
			// y-axis arrowhead 
			var ya:Array = new Array(
				new Vertex3D(0,        tSize - ahHeight, -ahWidth),
				new Vertex3D(-ahWidth, tSize - ahHeight, 0),
				new Vertex3D(0,        tSize - ahHeight, ahWidth),
				new Vertex3D(ahWidth,  tSize - ahHeight, 0)
			);
			
			drawArrowHead(arr, vy, ya, material);
			return arr;
		}
		
		private function drawArrowX(material:LineMaterial): Lines3D {
			var arr:Lines3D = new Lines3D();
			
			// x-axis arrow shaft
			var v0:Vertex3D = new Vertex3D();
			var vx:Vertex3D = new Vertex3D(tSize, 0, 0);
			var xShaft:Line3D = new Line3D(arr, material, thickness, v0, vx);
			arr.addLine(xShaft);
			
			// x-axis arrowhead 
			var xa:Array = new Array(
				new Vertex3D(tSize - ahHeight, 0,        -ahWidth),
				new Vertex3D(tSize - ahHeight, -ahWidth, 0),
				new Vertex3D(tSize - ahHeight, 0,        ahWidth),
				new Vertex3D(tSize - ahHeight, ahWidth,  0)
			);
			
			drawArrowHead(arr, vx, xa, material);
			return arr;
		}
		
		private function drawArrowHead(arr:Lines3D, arrowPointingVertex:Vertex3D, 
										arrowHeadVertices:Array, material:LineMaterial): void {
			
			for(var i:int = 0; i < arrowHeadVertices.length; i++) {
				arr.addLine(new Line3D(arr, material, thickness, arrowHeadVertices[i], 
									   arrowHeadVertices[(i + 1) % arrowHeadVertices.length]));
			}
			
			for(var i:int = 0; i < arrowHeadVertices.length; i++) {
				arr.addLine(new Line3D(arr, material, thickness, arrowHeadVertices[i], arrowPointingVertex));
			}
		}
	}
}