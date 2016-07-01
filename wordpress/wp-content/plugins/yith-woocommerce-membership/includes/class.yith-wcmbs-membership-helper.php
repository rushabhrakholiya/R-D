<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Member Class
 *
 * @class   YITH_WCMBS_Membership
 * @package Yithemes
 * @since   1.0.0
 * @author  Yithemes
 *
 */
class YITH_WCMBS_Membership_Helper {

    /**
     * Single instance of the class
     *
     * @var \YITH_WCMBS_Membership_Helper
     */
    protected static $instance;

    /**
     * the membership post type name
     *
     * @var string
     * @since 1.0.0
     */
    public $post_type_name = 'ywcmbs-membership';

    /**
     * Returns single instance of the class
     *
     * @return \YITH_WCMBS_Membership_Helper
     * @since 1.0.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor
     *
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     */
    public function __construct() {
    }

    /**
     * Get all memberships of a user
     *
     *
     * @param int $user_id the id of the user
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @return YITH_WCMBS_Membership[]|bool
     */
    public function get_memberships_by_user( $user_id ) {
        $memberships = get_posts( array(
            'post_type'                  => $this->post_type_name,
            'posts_per_page'            => -1,
            'meta_key'                   => '_user_id',
            'meta_value'                 => $user_id,
            'suppress_filter'            => true,
            'yith_wcmbs_suppress_filter' => true,
        ) );

        return $this->parse_memberships_from_posts( $memberships );
    }

    /**
     * Get all memberships by meta_query
     *
     *
     * @param array $meta_query the id of the user
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @return YITH_WCMBS_Membership[]|bool
     */
    public function get_memberships_by_meta( $meta_query ) {
        $memberships = get_posts( array(
            'post_type'                  => $this->post_type_name,
            'posts_per_page'            => -1,
            'meta_query'                 => $meta_query,
            'suppress_filter'            => true,
            'yith_wcmbs_suppress_filter' => true,
        ) );

        return $this->parse_memberships_from_posts( $memberships );
    }

    /**
     * get membership by subscription id
     *
     * @param int $subscription_id the id of the subscription
     *
     * @access public
     * @since  1.0.0
     * @return bool|YITH_WCMBS_Membership
     */
    public function get_memberships_by_subscription( $subscription_id ) {
        $meta_query = array(
            array(
                'key'   => '_subscription_id',
                'value' => $subscription_id,
            )
        );

        return $this->get_memberships_by_meta( $meta_query );
    }

    /**
     * get memberships by order
     *
     * @param int $order_id      the id of the order
     * @param int $order_item_id the id of the order item
     *
     * @access public
     * @since  1.0.0
     * @return bool|YITH_WCMBS_Membership
     */
    public function get_memberships_by_order( $order_id, $order_item_id ) {
        $meta_query = array(
            'relation' => 'AND',
            array(
                'key'   => '_order_id',
                'value' => $order_id,
            ),
            array(
                'key'   => '_order_item_id',
                'value' => $order_item_id,
            )
        );

        return $this->get_memberships_by_meta( $meta_query );
    }

    /**
     * Get all memberships
     *
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @return YITH_WCMBS_Membership[]|bool
     */
    public function get_all_memberships() {
        $memberships = get_posts( array(
            'post_type'                  => $this->post_type_name,
            'posts_per_page'            => -1,
            'suppress_filter'            => true,
            'yith_wcmbs_suppress_filter' => true,
        ) );

        return $this->parse_memberships_from_posts( $memberships );
    }

    /**
     * Get all memberships by plan_id
     *
     * @param int $plan_id the id of the plan
     *
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @return YITH_WCMBS_Membership[]|bool
     */
    public function get_all_memberships_by_plan_id( $plan_id ) {
        $memberships = get_posts( array(
            'post_type'                  => $this->post_type_name,
            'posts_per_page'            => -1,
            'meta_key'                   => '_plan_id',
            'meta_value'                 => $plan_id,
            'suppress_filter'            => true,
            'yith_wcmbs_suppress_filter' => true,
        ) );

        return $this->parse_memberships_from_posts( $memberships );
    }

    /**
     * parse posts and return array of YITH_WC
     *
     * @param WP_Post|WP_Post[] $membership_posts the posts
     *
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @return YITH_WCMBS_Membership[]|bool
     */
    public function parse_memberships_from_posts( $membership_posts ) {
        if ( !empty( $membership_posts ) ) {
            $membership_posts = (array)$membership_posts;
            $memberships      = [ ];
            foreach ( $membership_posts as $post ) {
                $membership    = new YITH_WCMBS_Membership( $post->ID );
                $memberships[] = $membership;
            }

            return $memberships;
        }

        return false;
    }

    /**
     * get the linked plans ids by $plan_id
     * return false if the plan don't have linked plans
     *
     * @access public
     * @since  1.0.0
     *
     * @return array|bool
     */
    public function get_linked_plans( $plan_id ) {
        $linked_plans = get_post_meta( $plan_id, '_linked-plans', true );

        return !empty( $linked_plans ) ? $linked_plans : array();
    }

    /**
     * Get all members for a plan
     *
     *
     * @param int $plan_id the id of the plan
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @return array
     */
    public function get_members( $plan_id, $args = array() ) {

        $default_args = array(
            'return' => 'ids'
        );

        $args = wp_parse_args( $args, $default_args );

        $memberships = $this->get_all_memberships_by_plan_id( $plan_id );

        $r = array();

        if ( !empty( $memberships ) ) {
            foreach ( $memberships as $membership ) {
                if ( $membership instanceof YITH_WCMBS_Membership ) {
                    if ( $membership->is_active() ) {
                        if ( $args[ 'return' ] == 'ids' ) {
                            $r[] = $membership->user_id;
                        }
                    }
                }
            }
        }

        return array_unique( $r );

    }

}

/**
 * Unique access to instance of YITH_WCMBS_Membership_Helper class
 *
 * @return \YITH_WCMBS_Membership_Helper
 */
function YITH_WCMBS_Membership_Helper() {
    return YITH_WCMBS_Membership_Helper::get_instance();
}
