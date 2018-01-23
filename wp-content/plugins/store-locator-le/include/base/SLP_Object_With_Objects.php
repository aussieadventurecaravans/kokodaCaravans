<?php
if ( ! class_exists( 'SLP_Object_With_Objects' ) ) :

	/**
	 * Class SLP_Object_With_Objects
	 *
	 * @package   StoreLocatorPlus/Object/With_Objects
	 * @author    Lance Cleveland <lance@cybersprocket.com>
	 * @copyright 2016 Cyber Sprocket Labs, LLC
	 *
	 * @var   string $class_prefix  the prefix that goes before all our classes
	 *
	 * @var   string  $dir          the root directory for this theme
	 *
	 * @var   array   $objects      key = class name, array of attributes()
	 *                                  auto_instantiate = true , instantiate the object when this object initializes
	 *                                  object  = the instantiated object
	 *                                  options = default startup options
	 *                                  subdir  = the subdirectory (from theme root) that contains the class definition
	 */
	class SLP_Object_With_Objects extends SLPlus_BaseClass_Object {
		protected $class_prefix;
		public $dir;
		protected $objects = array();

		/**
		 * Get the value, running it through a filter.
		 *
		 * @param string $property
		 *
		 * @return mixed     null if not set or the value
		 */
		function __get( $property ) {

			if ( array_key_exists( $property , $this->objects ) ) {
				return $this->objects[ $property ]['object'];
			}

			return null;
		}

		/**
		 * Allow isset to be called on private properties.
		 *
		 * @param $property
		 *
		 * @return bool
		 */
		public function __isset( $property ) {

			if ( array_key_exists( $property , $this->objects ) && is_object( $this->objects[ $property ]['object'] ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Augment the class names so we can reference simple property names.
		 *
		 * @param string $class
		 *
		 * @return string
		 */
		private function augment_class( $class ) {
			return $this->class_prefix . $class;
		}

		/**
		 * Any object with auto_instantiate set to true is instantiate during initialization.
		 */
		private function auto_instantiate() {
			if ( ! empty ( $this->objects ) ){
				foreach ( $this->objects as $slug => $properties ) {
					if ( ! empty( $properties[ 'auto_instantiate' ] ) ) {
						$options = ! empty ( $properties[ 'options' ] ) ? $properties[ 'options' ] : array() ;
						$this->instantiate( $slug , $options );
					}
				}
			}
		}

		/**
		 * Set the dir property.
		 */
		protected function initialize() {
			$this->class_prefix = empty( $this->class_prefix ) ? 'SLP_' : $this->class_prefix;
			$this->dir = empty( $this->dir ) ? SLPLUS_PLUGINDIR : $this->dir;

			$this->set_default_object_options();
			$this->auto_instantiate();
		}

		/**
		 * Instantiate an object of the noted class.
		 *
		 * @param string $class
		 * @param array  $options
		 *
		 * @return null|object
		 */
		public function instantiate( $class , $options = array() ) {
			if ( ! array_key_exists( $class, $this->objects ) ) {
				return null;
			}
			if ( ! isset( $this->$class ) ) {
				$full_class = $this->augment_class( $class );
				include_once( $this->dir . '/' . $this->objects[$class]['subdir'] . $full_class .'.php' );
				if ( ! class_exists( $full_class ) ) {
					return null;
				}
				$this->objects[ $class ]['object'] = new $full_class( $options );
			}
			return $this->class;
		}

		/**
		 * Set default options for objects.  Override in your class.
		 */
		protected function set_default_object_options() {}
	}

endif;

