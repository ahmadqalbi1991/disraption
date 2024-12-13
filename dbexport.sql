--
-- PostgreSQL database dump
--

-- Dumped from database version 12.20 (Ubuntu 12.20-1.pgdg20.04+1)
-- Dumped by pg_dump version 15.8 (Ubuntu 15.8-1.pgdg20.04+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: app_banners; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.app_banners (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    banner_image character varying(1500),
    active integer DEFAULT 1 NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.app_banners OWNER TO postgres;

--
-- Name: app_banners_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.app_banners_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.app_banners_id_seq OWNER TO postgres;

--
-- Name: app_banners_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.app_banners_id_seq OWNED BY public.app_banners.id;


--
-- Name: articles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.articles (
    id bigint NOT NULL,
    title_en character varying(255),
    title_ar character varying(255),
    status integer DEFAULT 1 NOT NULL,
    desc_en text,
    desc_ar text,
    meta_title text,
    meta_keyword text,
    meta_description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.articles OWNER TO postgres;

--
-- Name: articles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.articles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.articles_id_seq OWNER TO postgres;

--
-- Name: articles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.articles_id_seq OWNED BY public.articles.id;


--
-- Name: booking_orders; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.booking_orders (
    id bigint NOT NULL,
    customer_id bigint NOT NULL,
    vendor_id bigint NOT NULL,
    booking_id bigint NOT NULL,
    reference_number character varying(255) NOT NULL,
    status character varying(255) NOT NULL,
    total_paid numeric(10,2) NOT NULL,
    tax numeric(10,2) NOT NULL,
    discount numeric(10,2) NOT NULL,
    order_id character varying(50),
    is_rescheduled integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.booking_orders OWNER TO postgres;

--
-- Name: booking_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.booking_orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.booking_orders_id_seq OWNER TO postgres;

--
-- Name: booking_orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.booking_orders_id_seq OWNED BY public.booking_orders.id;


--
-- Name: booking_resources; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.booking_resources (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    active smallint DEFAULT '1'::smallint NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.booking_resources OWNER TO postgres;

--
-- Name: booking_resources_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.booking_resources_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.booking_resources_id_seq OWNER TO postgres;

--
-- Name: booking_resources_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.booking_resources_id_seq OWNED BY public.booking_resources.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: category; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.category (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    image character varying(1500),
    active integer DEFAULT 1 NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.category OWNER TO postgres;

--
-- Name: category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.category_id_seq OWNER TO postgres;

--
-- Name: category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.category_id_seq OWNED BY public.category.id;


--
-- Name: contact_us_entries; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contact_us_entries (
    id bigint NOT NULL,
    customer_id bigint,
    name character varying(255),
    email character varying(255),
    dial_code character varying(255),
    phone character varying(255),
    message character varying(4000),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.contact_us_entries OWNER TO postgres;

--
-- Name: contact_us_entries_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contact_us_entries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contact_us_entries_id_seq OWNER TO postgres;

--
-- Name: contact_us_entries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.contact_us_entries_id_seq OWNED BY public.contact_us_entries.id;


--
-- Name: country; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.country (
    id bigint NOT NULL,
    name character varying(1500) NOT NULL,
    prefix character varying(20) NOT NULL,
    dial_code character varying(100) NOT NULL,
    active character varying(255) DEFAULT '1'::character varying NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.country OWNER TO postgres;

--
-- Name: country_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.country_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.country_id_seq OWNER TO postgres;

--
-- Name: country_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.country_id_seq OWNED BY public.country.id;


--
-- Name: customer_ratings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.customer_ratings (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    vendor_id bigint NOT NULL,
    rating integer NOT NULL,
    review text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.customer_ratings OWNER TO postgres;

--
-- Name: customer_ratings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.customer_ratings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.customer_ratings_id_seq OWNER TO postgres;

--
-- Name: customer_ratings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.customer_ratings_id_seq OWNED BY public.customer_ratings.id;


--
-- Name: customer_user_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.customer_user_details (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    date_of_birth date,
    lattitude character varying(255),
    longitude character varying(255),
    location_name character varying(255),
    gender character varying(255),
    is_social integer DEFAULT 0 NOT NULL,
    wallet_balance numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    total_rating numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    wallet_id character varying(13),
    remarks character varying(255),
    CONSTRAINT customer_user_details_gender_check CHECK (((gender)::text = ANY ((ARRAY['male'::character varying, 'female'::character varying, 'other'::character varying])::text[])))
);


ALTER TABLE public.customer_user_details OWNER TO postgres;

--
-- Name: customer_user_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.customer_user_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.customer_user_details_id_seq OWNER TO postgres;

--
-- Name: customer_user_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.customer_user_details_id_seq OWNED BY public.customer_user_details.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: favourites; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.favourites (
    id bigint NOT NULL,
    vendor_id bigint,
    customer_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.favourites OWNER TO postgres;

--
-- Name: favourites_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.favourites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.favourites_id_seq OWNER TO postgres;

--
-- Name: favourites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.favourites_id_seq OWNED BY public.favourites.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO postgres;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.personal_access_tokens_id_seq OWNER TO postgres;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: role_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_permissions (
    id bigint NOT NULL,
    user_role_id_fk integer NOT NULL,
    module_key character varying(255) NOT NULL,
    permissions text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.role_permissions OWNER TO postgres;

--
-- Name: role_permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.role_permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.role_permissions_id_seq OWNER TO postgres;

--
-- Name: role_permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.role_permissions_id_seq OWNED BY public.role_permissions.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    role character varying(255) NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    is_admin_role integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.roles_id_seq OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.settings (
    id bigint NOT NULL,
    meta_key character varying(255) NOT NULL,
    meta_value character varying(255) NOT NULL
);


ALTER TABLE public.settings OWNER TO postgres;

--
-- Name: settings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.settings_id_seq OWNER TO postgres;

--
-- Name: settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.settings_id_seq OWNED BY public.settings.id;


--
-- Name: temp_transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.temp_transactions (
    id bigint NOT NULL,
    type character varying(50),
    p_id character varying(100),
    p_status character varying(50),
    transaction_data text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.temp_transactions OWNER TO postgres;

--
-- Name: temp_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.temp_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.temp_transactions_id_seq OWNER TO postgres;

--
-- Name: temp_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.temp_transactions_id_seq OWNED BY public.temp_transactions.id;


--
-- Name: temp_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.temp_users (
    id bigint NOT NULL,
    name character varying(255),
    email character varying(255),
    dial_code character varying(255),
    phone character varying(255) NOT NULL,
    user_type_id integer,
    user_phone_otp character varying(255),
    access_token character varying(255) NOT NULL,
    user_data json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.temp_users OWNER TO postgres;

--
-- Name: temp_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.temp_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.temp_users_id_seq OWNER TO postgres;

--
-- Name: temp_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.temp_users_id_seq OWNED BY public.temp_users.id;


--
-- Name: transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transactions (
    id bigint NOT NULL,
    customer_id bigint NOT NULL,
    vendor_id bigint,
    order_id bigint,
    transaction_id character varying(50),
    status character varying(255) NOT NULL,
    amount numeric(10,2) NOT NULL,
    type character varying(255) NOT NULL,
    payment_method character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    other_customer_id bigint,
    p_trans_id character varying(255),
    p_info character varying(255),
    p_data character varying(255)
);


ALTER TABLE public.transactions OWNER TO postgres;

--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.transactions_id_seq OWNER TO postgres;

--
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transactions_id_seq OWNED BY public.transactions.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255),
    email character varying(255),
    dial_code character varying(255),
    phone character varying(255) NOT NULL,
    phone_verified integer DEFAULT 0 NOT NULL,
    password character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    role character varying(255),
    verified integer DEFAULT 0 NOT NULL,
    user_type_id integer,
    first_name character varying(255),
    last_name character varying(255),
    user_image character varying(255),
    user_phone_otp character varying(255),
    active integer DEFAULT 1 NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role_id bigint,
    device_type character varying(255),
    fcm_token character varying(255),
    device_cart_id character varying(255),
    password_reset_code character varying(255),
    req_chng_email character varying(255),
    req_chng_phone character varying(255),
    req_chng_dial_code character varying(255),
    deleted_at timestamp(0) without time zone,
    last_login timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: users_role; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_role (
    id bigint NOT NULL,
    role_name character varying(200) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users_role OWNER TO postgres;

--
-- Name: users_role_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_role_id_seq OWNER TO postgres;

--
-- Name: users_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_role_id_seq OWNED BY public.users_role.id;


--
-- Name: vendor_booking_dates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vendor_booking_dates (
    id bigint NOT NULL,
    booking_id bigint NOT NULL,
    date date,
    start_time time(6) without time zone,
    end_time time(6) without time zone,
    resource_id bigint
);


ALTER TABLE public.vendor_booking_dates OWNER TO postgres;

--
-- Name: vendor_booking_dates_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vendor_booking_dates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vendor_booking_dates_id_seq OWNER TO postgres;

--
-- Name: vendor_booking_dates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vendor_booking_dates_id_seq OWNED BY public.vendor_booking_dates.id;


--
-- Name: vendor_booking_media; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vendor_booking_media (
    id bigint NOT NULL,
    filename character varying(255) NOT NULL,
    vendor_booking_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.vendor_booking_media OWNER TO postgres;

--
-- Name: vendor_booking_media_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vendor_booking_media_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vendor_booking_media_id_seq OWNER TO postgres;

--
-- Name: vendor_booking_media_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vendor_booking_media_id_seq OWNED BY public.vendor_booking_media.id;


--
-- Name: vendor_bookings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vendor_bookings (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    reference_number character varying(255) NOT NULL,
    total numeric(10,2) NOT NULL,
    advance numeric(10,2) NOT NULL,
    order_id character varying(50),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id bigint,
    status character varying(255),
    total_paid numeric(10,2),
    tax numeric(10,2),
    discount numeric(10,2),
    is_rescheduled integer DEFAULT 0 NOT NULL,
    hourly_rate numeric(10,2),
    total_with_tax numeric(10,2),
    total_without_tax numeric(10,2),
    total_hours numeric(10,2),
    last_payment_method character varying(255),
    temp_reschedule_data text,
    before_reschedule_dates text,
    total_rschdl_paid integer DEFAULT 0 NOT NULL,
    disraption double precision DEFAULT '0'::double precision NOT NULL,
    artist_commission double precision DEFAULT '0'::double precision NOT NULL,
    neworer_commission double precision DEFAULT '0'::double precision NOT NULL,
    gateway double precision DEFAULT '0'::double precision NOT NULL,
    cancel_remarks text,
    is_refund_made boolean DEFAULT false NOT NULL,
    refund_file character varying(255),
    duration numeric(10,2)
);


ALTER TABLE public.vendor_bookings OWNER TO postgres;

--
-- Name: vendor_bookings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vendor_bookings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vendor_bookings_id_seq OWNER TO postgres;

--
-- Name: vendor_bookings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vendor_bookings_id_seq OWNED BY public.vendor_bookings.id;


--
-- Name: vendor_portfolios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vendor_portfolios (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    title character varying(255),
    description character varying(255),
    filename character varying(255) NOT NULL,
    mime character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT vendor_portfolios_type_check CHECK (((type)::text = ANY ((ARRAY['image'::character varying, 'video'::character varying])::text[])))
);


ALTER TABLE public.vendor_portfolios OWNER TO postgres;

--
-- Name: vendor_portfolios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vendor_portfolios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vendor_portfolios_id_seq OWNER TO postgres;

--
-- Name: vendor_portfolios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vendor_portfolios_id_seq OWNED BY public.vendor_portfolios.id;


--
-- Name: vendor_ratings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vendor_ratings (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    vendor_id bigint NOT NULL,
    rating integer NOT NULL,
    review text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    booking_id bigint
);


ALTER TABLE public.vendor_ratings OWNER TO postgres;

--
-- Name: vendor_ratings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vendor_ratings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vendor_ratings_id_seq OWNER TO postgres;

--
-- Name: vendor_ratings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vendor_ratings_id_seq OWNED BY public.vendor_ratings.id;


--
-- Name: vendor_user_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vendor_user_details (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    username character varying(50) NOT NULL,
    date_of_birth date,
    lattitude character varying(255),
    longitude character varying(255),
    location_name character varying(255),
    about text,
    instagram character varying(255),
    twitter character varying(255),
    facebook character varying(255),
    tiktok character varying(255),
    gender character varying(255),
    c_policy text,
    r_policy text,
    reference_number character varying(255) NOT NULL,
    hourly_rate numeric(8,2),
    advance_percent integer DEFAULT 0 NOT NULL,
    availability_from date,
    category_id bigint,
    type character varying(255) DEFAULT 'resident'::character varying NOT NULL,
    total_rating numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    thread character varying(255),
    availability_to date,
    deposit_amount numeric(8,2),
    categories character varying(600),
    CONSTRAINT vendor_user_details_gender_check CHECK (((gender)::text = ANY ((ARRAY['male'::character varying, 'female'::character varying, 'other'::character varying])::text[]))),
    CONSTRAINT vendor_user_details_type_check CHECK (((type)::text = ANY ((ARRAY['resident'::character varying, 'guest'::character varying])::text[])))
);


ALTER TABLE public.vendor_user_details OWNER TO postgres;

--
-- Name: vendor_user_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vendor_user_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vendor_user_details_id_seq OWNER TO postgres;

--
-- Name: vendor_user_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vendor_user_details_id_seq OWNED BY public.vendor_user_details.id;


--
-- Name: app_banners id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_banners ALTER COLUMN id SET DEFAULT nextval('public.app_banners_id_seq'::regclass);


--
-- Name: articles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.articles ALTER COLUMN id SET DEFAULT nextval('public.articles_id_seq'::regclass);


--
-- Name: booking_orders id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.booking_orders ALTER COLUMN id SET DEFAULT nextval('public.booking_orders_id_seq'::regclass);


--
-- Name: booking_resources id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.booking_resources ALTER COLUMN id SET DEFAULT nextval('public.booking_resources_id_seq'::regclass);


--
-- Name: category id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category ALTER COLUMN id SET DEFAULT nextval('public.category_id_seq'::regclass);


--
-- Name: contact_us_entries id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact_us_entries ALTER COLUMN id SET DEFAULT nextval('public.contact_us_entries_id_seq'::regclass);


--
-- Name: country id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.country ALTER COLUMN id SET DEFAULT nextval('public.country_id_seq'::regclass);


--
-- Name: customer_ratings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.customer_ratings ALTER COLUMN id SET DEFAULT nextval('public.customer_ratings_id_seq'::regclass);


--
-- Name: customer_user_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.customer_user_details ALTER COLUMN id SET DEFAULT nextval('public.customer_user_details_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: favourites id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favourites ALTER COLUMN id SET DEFAULT nextval('public.favourites_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: role_permissions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permissions ALTER COLUMN id SET DEFAULT nextval('public.role_permissions_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: settings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.settings ALTER COLUMN id SET DEFAULT nextval('public.settings_id_seq'::regclass);


--
-- Name: temp_transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.temp_transactions ALTER COLUMN id SET DEFAULT nextval('public.temp_transactions_id_seq'::regclass);


--
-- Name: temp_users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.temp_users ALTER COLUMN id SET DEFAULT nextval('public.temp_users_id_seq'::regclass);


--
-- Name: transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions ALTER COLUMN id SET DEFAULT nextval('public.transactions_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: users_role id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_role ALTER COLUMN id SET DEFAULT nextval('public.users_role_id_seq'::regclass);


--
-- Name: vendor_booking_dates id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_booking_dates ALTER COLUMN id SET DEFAULT nextval('public.vendor_booking_dates_id_seq'::regclass);


--
-- Name: vendor_booking_media id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_booking_media ALTER COLUMN id SET DEFAULT nextval('public.vendor_booking_media_id_seq'::regclass);


--
-- Name: vendor_bookings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_bookings ALTER COLUMN id SET DEFAULT nextval('public.vendor_bookings_id_seq'::regclass);


--
-- Name: vendor_portfolios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_portfolios ALTER COLUMN id SET DEFAULT nextval('public.vendor_portfolios_id_seq'::regclass);


--
-- Name: vendor_ratings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_ratings ALTER COLUMN id SET DEFAULT nextval('public.vendor_ratings_id_seq'::regclass);


--
-- Name: vendor_user_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_user_details ALTER COLUMN id SET DEFAULT nextval('public.vendor_user_details_id_seq'::regclass);


--
-- Data for Name: app_banners; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.app_banners (id, name, banner_image, active, sort_order, created_at, updated_at) FROM stdin;
1	Banner 1	6661a1d6667a9_1717674454.png	1	0	2024-06-06 15:47:34	2024-08-14 19:22:59
2	Banner 23	6661a2206a277_1717674528.png	1	0	2024-06-06 15:48:48	2024-08-15 09:40:18
\.


--
-- Data for Name: articles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.articles (id, title_en, title_ar, status, desc_en, desc_ar, meta_title, meta_keyword, meta_description, created_at, updated_at) FROM stdin;
3	Privacy Policy	\N	1	<p>Privacy policy--Others who use this device won&rsquo;t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google. Downloads, bookmarks and reading list items will be saved.</p>	\N	\N	\N	\N	2024-06-10 11:46:37	2024-06-11 16:12:37
4	Terms & Conditions	\N	1	<p>Terms &amp; Conditions--&nbsp;Others who use this device won&rsquo;t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google. Downloads, bookmarks and reading list items will be saved.</p>	\N	\N	\N	\N	2024-06-10 11:47:10	2024-06-11 16:13:01
5	Contact us	\N	1	<p>send an email to the studio here</p>	\N	\N	\N	\N	2024-06-10 11:47:26	2024-06-24 15:30:39
1	About Us	\N	1	<p>About us ---Say something here abnout is&nbsp;</p>	\N	\N	\N	\N	2024-05-01 11:56:48	2024-06-24 15:31:13
6	Deposit policy	\N	1	<p>Deposit policy</p>	\N	\N	\N	\N	2024-07-05 13:15:33	2024-07-05 13:15:33
7	Cancelation Policy	\N	1	<p>Cancelation Policy</p>	\N	\N	\N	\N	2024-07-05 13:15:34	2024-07-05 13:18:05
8	Rescheduling Policy	\N	1	<p>Rescheduling Policy</p>	\N	\N	\N	\N	2024-07-05 13:19:17	2024-07-05 13:19:17
9	Pricing	\N	1	<p>Pricing</p>	\N	\N	\N	\N	2024-07-05 13:19:30	2024-07-05 13:19:30
\.


--
-- Data for Name: booking_orders; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.booking_orders (id, customer_id, vendor_id, booking_id, reference_number, status, total_paid, tax, discount, order_id, is_rescheduled, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: booking_resources; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.booking_resources (id, name, active, deleted) FROM stdin;
6	dfgdfg	1	1
7	ghdhd	1	1
9	fcghdgh	1	1
8	dghtyuuuuu	1	1
10	city	1	1
11	xvxv	1	1
12	workstation 1	1	0
1	workstation 6	1	0
5	workstation 2	1	0
2	workstation 5	1	0
3	workstation 4	1	0
4	workstation 3	1	0
13	workstation joker	1	1
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.category (id, name, image, active, sort_order, created_at, updated_at, deleted_at) FROM stdin;
1	tes	66c0bb079f0ef_1723906823.jpeg	1	0	2024-08-17 19:00:24	2024-08-17 19:00:30	2024-08-17 19:00:30
2	Testing	66c0e773360e7_1723918195.jpeg	1	0	2024-08-17 22:09:55	2024-08-17 22:28:51	2024-08-17 22:28:51
3	df	66c2c5a71b644_1724040615.jpg	1	0	2024-08-19 08:10:15	2024-08-19 08:13:27	2024-08-19 08:13:27
\.


--
-- Data for Name: contact_us_entries; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contact_us_entries (id, customer_id, name, email, dial_code, phone, message, created_at, updated_at) FROM stdin;
1	29	Abdul Wahab	wahabfun22@gmail.com	92	3110413143	Testing	2024-06-11 13:54:07	2024-06-11 13:54:07
2	29	test two	test2@gmail.com	971	369369369	test message	2024-06-11 14:24:11	2024-06-11 14:24:11
3	30	Nemai B	u1@mailinator.com	+92	9333669963	Saqdssad	2024-06-13 00:11:01	2024-06-13 00:11:01
4	33	Test Three	test3@gmail.com	971	12369807412	tedt message	2024-06-13 02:12:10	2024-06-13 02:12:10
5	33	Test Three	test3@gmail.com	971	12369807412	message	2024-06-13 02:13:48	2024-06-13 02:13:48
6	50	Nemai Eleven	u13@mailinator.com	971	96385274126	Very good 💯	2024-06-13 08:47:52	2024-06-13 08:47:52
7	76	N Seventeen	u17@mailinator.com	971	933258239633	Message and notification count is not	2024-06-14 09:36:42	2024-06-14 09:36:42
\.


--
-- Data for Name: country; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.country (id, name, prefix, dial_code, active, deleted, created_at, updated_at) FROM stdin;
2	Aland Islands	AX	358	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
3	Albania	AL	355	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
4	Algeria	DZ	213	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
6	Andorra	AD	376	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
7	Angola	AO	244	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
8	Anguilla	AI	1264	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
9	Antarctica	AQ	672	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
10	Antigua and Barbuda	AG	1268	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
11	Argentina	AR	54	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
12	Armenia	AM	374	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
13	Aruba	AW	297	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
14	Australia	AU	61	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
15	Austria	AT	43	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
16	Azerbaijan	AZ	994	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
17	Bahamas	BS	1242	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
18	Bahrain	BH	973	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
19	Bangladesh	BD	880	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
20	Barbados	BB	1246	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
21	Belarus	BY	375	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
22	Belgium	BE	32	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
23	Belize	BZ	501	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
24	Benin	BJ	229	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
25	Bermuda	BM	1441	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
26	Bhutan	BT	975	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
27	Bolivia, Plurinational State of	BO	591	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
28	Bosnia and Herzegovina	BA	387	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
29	Botswana	BW	267	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
30	Brazil	BR	55	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
31	British Indian Ocean Territory	IO	246	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
32	Brunei Darussalam	BN	673	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
33	Bulgaria	BG	359	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
34	Burkina Faso	BF	226	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
35	Burundi	BI	257	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
36	Cambodia	KH	855	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
37	Cameroon	CM	237	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
38	Canada	CA	1	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
39	Cape Verde	CV	238	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
40	Cayman Islands	KY	 345	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
41	Central African Republic	CF	236	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
42	Chad	TD	235	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
43	Chile	CL	56	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
44	China	CN	86	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
45	Christmas Island	CX	61	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
46	Cocos (Keeling) Islands	CC	61	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
47	Colombia	CO	57	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
48	Comoros	KM	269	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
49	Congo	CG	242	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
50	Congo, The Democratic Republic of the Congo	CD	243	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
51	Cook Islands	CK	682	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
52	Costa Rica	CR	506	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
53	Cote d'Ivoire	CI	225	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
54	Croatia	HR	385	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
55	Cuba	CU	53	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
56	Cyprus	CY	357	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
57	Czech Republic	CZ	420	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
58	Denmark	DK	45	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
59	Djibouti	DJ	253	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
60	Dominica	DM	1767	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
61	Dominican Republic	DO	1849	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
62	Ecuador	EC	593	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
63	Egypt	EG	20	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
64	El Salvador	SV	503	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
65	Equatorial Guinea	GQ	240	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
66	Eritrea	ER	291	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
67	Estonia	EE	372	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
68	Ethiopia	ET	251	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
69	Falkland Islands (Malvinas)	FK	500	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
70	Faroe Islands	FO	298	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
71	Fiji	FJ	679	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
72	Finland	FI	358	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
73	France	FR	33	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
74	French Guiana	GF	594	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
75	French Polynesia	PF	689	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
76	Gabon	GA	241	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
77	Gambia	GM	220	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
78	Georgia	GE	995	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
79	Germany	DE	49	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
80	Ghana	GH	233	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
81	Gibraltar	GI	350	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
82	Greece	GR	30	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
83	Greenland	GL	299	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
84	Grenada	GD	1473	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
85	Guadeloupe	GP	590	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
86	Guam	GU	1671	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
87	Guatemala	GT	502	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
88	Guernsey	GG	44	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
89	Guinea	GN	224	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
90	Guinea-Bissau	GW	245	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
91	Guyana	GY	595	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
92	Haiti	HT	509	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
93	Holy See (Vatican City State)	VA	379	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
94	Honduras	HN	504	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
95	Hong Kong	HK	852	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
96	Hungary	HU	36	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
97	Iceland	IS	354	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
98	India	IN	91	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
99	Indonesia	ID	62	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
100	Iran, Islamic Republic of Persian Gulf	IR	98	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
101	Iraq	IQ	964	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
102	Ireland	IE	353	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
103	Isle of Man	IM	44	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
104	Israel	IL	972	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
1	Afghanistan	AF	9	1	0	2024-04-24 00:50:31	2024-08-07 09:07:20
105	Italy	IT	39	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
106	Jamaica	JM	1876	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
107	Japan	JP	81	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
108	Jersey	JE	44	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
109	Jordan	JO	962	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
110	Kazakhstan	KZ	77	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
111	Kenya	KE	254	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
112	Kiribati	KI	686	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
113	Korea, Democratic People's Republic of Korea	KP	850	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
114	Korea, Republic of South Korea	KR	82	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
115	Kuwait	KW	965	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
116	Kyrgyzstan	KG	996	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
117	Laos	LA	856	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
118	Latvia	LV	371	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
119	Lebanon	LB	961	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
120	Lesotho	LS	266	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
121	Liberia	LR	231	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
122	Libyan Arab Jamahiriya	LY	218	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
123	Liechtenstein	LI	423	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
124	Lithuania	LT	370	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
125	Luxembourg	LU	352	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
126	Macao	MO	853	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
127	Macedonia	MK	389	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
128	Madagascar	MG	261	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
129	Malawi	MW	265	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
130	Malaysia	MY	60	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
131	Maldives	MV	960	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
132	Mali	ML	223	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
133	Malta	MT	356	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
134	Marshall Islands	MH	692	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
135	Martinique	MQ	596	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
136	Mauritania	MR	222	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
137	Mauritius	MU	230	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
138	Mayotte	YT	262	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
139	Mexico	MX	52	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
140	Micronesia, Federated States of Micronesia	FM	691	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
141	Moldova	MD	373	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
142	Monaco	MC	377	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
143	Mongolia	MN	976	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
144	Montenegro	ME	382	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
145	Montserrat	MS	1664	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
146	Morocco	MA	212	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
147	Mozambique	MZ	258	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
148	Myanmar	MM	95	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
149	Namibia	NA	264	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
150	Nauru	NR	674	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
151	Nepal	NP	977	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
152	Netherlands	NL	31	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
153	Netherlands Antilles	AN	599	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
154	New Caledonia	NC	687	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
155	New Zealand	NZ	64	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
156	Nicaragua	NI	505	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
157	Niger	NE	227	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
158	Nigeria	NG	234	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
159	Niue	NU	683	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
160	Norfolk Island	NF	672	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
161	Northern Mariana Islands	MP	1670	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
162	Norway	NO	47	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
163	Oman	OM	968	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
164	Pakistan	PK	92	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
165	Palau	PW	680	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
166	Palestinian Territory, Occupied	PS	970	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
167	Panama	PA	507	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
168	Papua New Guinea	PG	675	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
169	Paraguay	PY	595	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
170	Peru	PE	51	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
171	Philippines	PH	63	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
172	Pitcairn	PN	872	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
173	Poland	PL	48	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
174	Portugal	PT	351	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
175	Puerto Rico	PR	1939	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
176	Qatar	QA	974	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
177	Romania	RO	40	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
178	Russia	RU	7	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
179	Rwanda	RW	250	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
180	Reunion	RE	262	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
181	Saint Barthelemy	BL	590	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
182	Saint Helena, Ascension and Tristan Da Cunha	SH	290	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
183	Saint Kitts and Nevis	KN	1869	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
185	Saint Martin	MF	590	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
186	Saint Pierre and Miquelon	PM	508	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
187	Saint Vincent and the Grenadines	VC	1784	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
188	Samoa	WS	685	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
189	San Marino	SM	378	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
190	Sao Tome and Principe	ST	239	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
191	Saudi Arabia	SA	966	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
192	Senegal	SN	221	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
193	Serbia	RS	381	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
194	Seychelles	SC	248	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
195	Sierra Leone	SL	232	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
196	Singapore	SG	65	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
197	Slovakia	SK	421	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
198	Slovenia	SI	386	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
199	Solomon Islands	SB	677	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
200	Somalia	SO	252	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
201	South Africa	ZA	27	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
202	South Sudan	SS	211	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
203	South Georgia and the South Sandwich Islands	GS	500	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
204	Spain	ES	34	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
205	Sri Lanka	LK	94	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
206	Sudan	SD	249	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
207	Suriname	SR	597	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
208	Svalbard and Jan Mayen	SJ	47	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
209	Swaziland	SZ	268	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
210	Sweden	SE	46	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
211	Switzerland	CH	41	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
212	Syrian Arab Republic	SY	963	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
213	Taiwan	TW	886	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
214	Tajikistan	TJ	992	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
215	Tanzania, United Republic of Tanzania	TZ	255	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
216	Thailand	TH	66	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
217	Timor-Leste	TL	670	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
218	Togo	TG	228	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
219	Tokelau	TK	690	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
220	Tonga	TO	676	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
221	Trinidad and Tobago	TT	1868	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
222	Tunisia	TN	216	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
223	Turkey	TR	90	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
224	Turkmenistan	TM	993	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
225	Turks and Caicos Islands	TC	1649	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
226	Tuvalu	TV	688	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
227	Uganda	UG	256	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
228	Ukraine	UA	380	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
229	United Arab Emirates	AE	971	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
230	United Kingdom	GB	44	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
231	United States	US	1	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
232	Uruguay	UY	598	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
233	Uzbekistan	UZ	998	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
234	Vanuatu	VU	678	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
235	Venezuela, Bolivarian Republic of Venezuela	VE	58	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
236	Vietnam	VN	84	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
237	Virgin Islands, British	VG	1284	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
238	Virgin Islands, U.S.	VI	1340	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
239	Wallis and Futuna	WF	681	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
240	Yemen	YE	967	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
241	Zambia	ZM	260	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
242	Zimbabwe	ZW	263	1	0	2024-04-24 00:50:31	2024-04-24 00:50:31
5	American Samoa	AS	1684	1	0	2024-04-24 00:50:31	2024-05-22 13:19:37
243	5654	DFG	45	0	1	2024-08-07 08:58:23	\N
246	rerggggggggggggggggggggggggggggggggggggggggggggggg	40540	4254	0	1	2024-08-08 05:40:40	\N
247	erter	456	43	0	1	2024-08-14 13:58:12	\N
248	asds	12	12	0	1	2024-08-14 15:03:46	\N
245	34	1	324	0	1	2024-08-07 09:00:56	\N
244	12	SC	23	0	1	2024-08-07 08:59:44	\N
184	Saint Lucia	LC	1758	1	0	2024-04-24 00:50:31	2024-08-15 04:58:10
249	qwerty	23	342	0	1	2024-08-15 04:07:16	2024-08-15 05:33:01
250	new	12	1232	1	0	2024-08-15 05:33:31	\N
\.


--
-- Data for Name: customer_ratings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.customer_ratings (id, user_id, vendor_id, rating, review, created_at, updated_at) FROM stdin;
1	9	10	4	good client	2024-05-22 18:21:53	2024-05-22 18:21:53
\.


--
-- Data for Name: customer_user_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.customer_user_details (id, user_id, date_of_birth, lattitude, longitude, location_name, gender, is_social, wallet_balance, created_at, updated_at, total_rating, wallet_id, remarks) FROM stdin;
69	93	2024-06-15	25.204819	55.270931		male	0	0.00	2024-06-16 11:36:16	2024-08-09 06:44:54	0.00	sp83188161745	\N
74	98	1998-08-14	25.204819	55.270931		male	0	10.00	2024-06-21 13:56:51	2024-08-09 06:45:11	0.00	sp41178815589	\N
70	94	1976-06-15	25.204819	55.270931		male	0	0.00	2024-06-16 11:52:00	2024-08-09 06:44:56	0.00	sp86725100321	\N
73	97	1994-08-06	25.204819	55.270931		male	0	7123.75	2024-06-20 18:49:59	2024-08-09 06:45:03	0.00	sp71819805833	\N
10	26	1993-04-06	25.204819	55.270931		male	0	500000.00	2024-06-06 09:36:56	2024-06-07 20:05:05	0.00	sp43243180973	\N
77	102	1993-04-06	25.204819	55.270931		male	0	0.00	2024-07-06 08:37:59	2024-08-14 10:54:07	0.00	sp84825841824	dfs
28	45	1941-06-05	25.204819	55.270931		male	0	2562.50	2024-06-12 06:42:26	2024-08-14 12:24:39	0.00	sp65585170097	ewetw
14	31	2024-06-07				male	0	0.00	2024-06-07 13:22:16	2024-06-08 02:46:50	0.00	sp99805562990	\N
15	32	\N				male	0	0.00	2024-06-10 12:45:41	2024-06-10 12:45:41	0.00	sp68655612697	\N
1	3	1993-05-12	25.204819	55.270931		male	0	500000.00	2024-05-01 11:59:10	2024-06-01 09:35:27	0.00	sp84304111986	\N
3	6	1997-05-15	25.204819	55.270931		male	0	500000.00	2024-05-06 02:11:24	2024-06-01 09:35:27	0.00	sp81944857706	\N
5	12	1993-04-06				male	0	500000.00	2024-05-24 05:59:47	2024-06-01 09:35:27	0.00	sp12254660700	\N
7	14	2023-05-25	25.204819	55.270931		male	0	500000.00	2024-05-25 07:53:27	2024-08-09 06:38:50	0.00	sp22492412735	\N
9	16	1997-08-25	25.204819	55.270931		male	0	500000.00	2024-06-05 15:27:16	2024-08-09 06:38:53	0.00	sp84323278580	\N
35	52	1997-06-11	25.204819	55.270931		male	0	0.00	2024-06-12 22:06:35	2024-08-09 06:42:07	0.00	sp54634348841	\N
30	47	2024-06-11	25.204819	55.270931		female	0	0.00	2024-06-12 08:57:35	2024-08-09 06:41:19	0.00	sp27344657664	\N
52	69	\N				\N	1	0.00	2024-06-13 20:53:10	2024-06-13 20:53:10	0.00	sp37687342709	\N
40	57	1984-08-10	25.204819	55.270931		male	0	0.00	2024-06-13 14:40:28	2024-08-09 06:42:38	0.00	sp94937211586	\N
66	88	1968-08-15	25.204819	55.270931		male	0	4920.00	2024-06-15 17:00:22	2024-08-09 06:44:08	0.00	sp75738533191	\N
53	70	\N				\N	1	0.00	2024-06-13 20:54:43	2024-06-13 20:54:43	0.00	sp10486145226	\N
72	96	2006-06-19	25.204819	55.270931		male	0	50.00	2024-06-20 14:13:16	2024-08-09 06:37:03	0.00	sp33842176407	\N
6	13	2022-05-24	25.204819	55.270931		male	0	500000.00	2024-05-24 11:42:34	2024-08-09 06:38:32	0.00	sp39073414680	\N
11	28	1999-06-05	25.204819	55.270931		male	0	494750.00	2024-06-06 10:32:14	2024-08-09 06:38:56	0.00	sp76107937097	\N
17	34	1995-07-11	25.204819	55.270931		male	0	0.00	2024-06-10 21:25:30	2024-08-09 06:39:34	0.00	sp46223501149	\N
47	64	\N				\N	0	0.00	2024-06-13 14:56:47	2024-06-13 14:57:00	0.00	sp24012717337	\N
19	36	1989-08-10	25.204819	55.270931		male	0	0.00	2024-06-11 12:45:07	2024-08-09 06:39:52	0.00	sp36928468777	\N
20	37	2000-06-10	25.204819	55.270931		male	0	0.00	2024-06-11 13:02:35	2024-08-09 06:39:56	0.00	sp16121776451	\N
21	38	1993-04-06	25.204819	55.270931		male	0	0.00	2024-06-11 13:09:49	2024-08-09 06:39:58	0.00	sp47142696398	\N
22	39	2013-06-10	25.204819	55.270931		male	0	0.00	2024-06-11 13:32:06	2024-08-09 06:40:26	0.00	sp77358831703	\N
24	41	1995-08-02	25.204819	55.270931		male	0	0.00	2024-06-11 14:42:13	2024-08-09 06:40:50	0.00	sp54523765609	\N
25	42	1993-04-05	25.204819	55.270931		male	0	0.00	2024-06-11 15:38:46	2024-08-09 06:40:53	0.00	sp85692967401	\N
26	43	2024-06-10	25.204819	55.270931		male	0	0.00	2024-06-11 17:25:21	2024-08-09 06:40:55	0.00	sp85608189305	\N
27	44	2024-06-10	25.204819	55.270931		male	0	11400.00	2024-06-11 19:50:52	2024-08-09 06:40:58	0.00	sp50818764801	\N
51	68	\N				\N	1	0.00	2024-06-13 20:48:59	2024-06-14 02:29:43	0.00	sp20904119421	\N
31	48	1869-08-12	25.204819	55.270931		male	0	0.00	2024-06-12 10:18:23	2024-08-09 06:41:28	0.00	sp81894011525	\N
32	49	2024-06-11	25.204819	55.270931		male	0	0.00	2024-06-12 10:39:46	2024-08-09 06:42:00	0.00	sp53051589113	\N
2	4	1993-04-06				male	0	499950.00	2024-05-03 13:57:18	2024-06-12 00:43:20	0.00	sp93242100538	\N
33	50	2006-06-12	25.204819	55.270931		male	0	846.00	2024-06-12 21:41:11	2024-08-09 06:42:03	0.00	sp25276290021	\N
36	53	1865-08-17	25.204819	55.270931		male	0	0.00	2024-06-13 10:09:05	2024-08-09 06:42:17	0.00	sp59013359199	\N
37	54	1993-04-06	25.204819	55.270931		male	0	10.00	2024-06-13 11:23:18	2024-08-09 06:42:19	0.00	sp21000489133	\N
54	76	2006-06-14				male	0	44471.75	2024-06-14 08:46:06	2024-06-14 09:34:57	0.00	sp69091845630	\N
38	55	1985-08-09	25.204819	55.270931		male	0	0.00	2024-06-13 14:30:06	2024-08-09 06:42:29	0.00	sp39195111506	\N
46	63	1993-04-06	25.204819	55.270931		male	0	0.00	2024-06-13 14:56:36	2024-08-09 06:42:40	0.00	sp10361037761	\N
55	77	\N				\N	1	0.00	2024-06-14 08:48:41	2024-06-14 10:47:57	0.00	sp54411277351	\N
48	65	2011-06-12	25.204819	55.270931		male	0	0.00	2024-06-13 15:06:40	2024-08-09 06:43:04	0.00	sp89131053350	\N
49	66	2011-06-12	25.204819	55.270931		male	0	5000.00	2024-06-13 16:33:23	2024-08-09 06:43:06	0.00	sp72536106156	\N
59	81	\N				\N	1	0.00	2024-06-14 10:55:38	2024-06-14 10:59:05	0.00	sp64233354812	\N
56	78	2000-06-13	25.204819	55.270931		male	0	0.00	2024-06-14 10:40:31	2024-08-09 06:43:10	0.00	sp71228114429	\N
57	79	1956-08-10	25.204819	55.270931		male	0	0.00	2024-06-14 10:46:46	2024-08-09 06:43:42	0.00	sp35231700520	\N
58	80	1998-08-06	25.204819	55.270931		male	1	41672.25	2024-06-14 10:50:45	2024-08-09 06:43:50	0.00	sp42591140729	\N
61	83	1947-08-14	25.204819	55.270931		male	1	0.00	2024-06-14 15:44:55	2024-08-09 06:43:58	0.00	sp79878502242	\N
65	87	2024-06-14	25.204819	55.270931		male	0	6274.50	2024-06-15 15:36:00	2024-08-09 06:44:01	0.00	sp88915730319	\N
67	89	1999-08-20	25.204819	55.270931		male	0	546800.00	2024-06-15 20:57:40	2024-08-09 06:44:18	0.00	sp69883012224	\N
12	29	\N				male	0	499530.00	2024-06-06 12:31:52	2024-06-21 13:42:43	0.00	sp92143893937	\N
60	82	2023-06-06	25.204819	55.270931		male	0	2370.00	2024-06-14 15:44:06	2024-06-20 13:05:22	0.00	sp79241617747	\N
8	15	1993-04-06				male	0	512732.00	2024-06-03 17:56:06	2024-06-15 12:53:50	0.00	sp76578100160	\N
62	84	2017-06-13	25.204819	55.270931		male	0	1304.75	2024-06-14 20:36:38	2024-06-15 20:21:27	0.00	sp98922490460	\N
63	85	\N				\N	0	342.50	2024-06-15 14:47:03	2024-06-15 16:48:07	0.00	sp59991835787	\N
71	95	1995-06-16				male	1	0.00	2024-06-17 10:42:17	2024-06-17 10:42:56	0.00	sp59832204345	\N
16	33	\N				male	0	130.50	2024-06-10 21:06:20	2024-06-21 13:42:43	0.00	sp69375292288	\N
68	92	1998-08-15	25.204819	55.270931		male	0	3358.75	2024-06-16 10:59:35	2024-08-09 06:44:51	0.00	sp70388655523	\N
75	99	1997-08-08	25.204819	55.270931		male	0	221.00	2024-06-21 15:42:07	2024-08-09 06:45:18	0.00	sp47689678297	\N
76	100	2003-06-29	25.204819	55.270931		male	0	0.00	2024-06-21 17:20:54	2024-08-09 06:45:21	0.00	sp83710844931	\N
78	109	\N	25.204819	55.270931		male	0	0.00	2024-08-14 12:29:51	2024-08-14 12:36:58	0.00	sp78472703080	\N
80	111	\N	25.204819	55.270931		male	0	0.00	2024-08-14 13:27:00	2024-08-14 13:27:00	0.00	sp73559096577	\N
81	120	\N	25.204819	55.270931		male	0	0.00	2024-08-14 17:16:55	2024-08-14 17:16:55	0.00	sp10369140180	\N
13	30	1998-06-05	25.204819	55.270931		male	0	1641323.25	2024-06-06 17:53:26	2024-08-09 06:39:22	0.00	sp40898254878	\N
4	9	1993-04-06	25.204819	55.270931		male	0	517987.50	2024-05-22 08:06:18	2024-08-14 17:21:06	0.00	sp65938488713	\N
18	35	1994-06-11	25.204819	55.270931		male	0	84325.00	2024-06-11 10:26:00	2024-08-09 06:39:37	0.00	sp94528510642	\N
23	40	2013-06-10	25.204819	55.270931		male	0	0.00	2024-06-11 13:37:53	2024-08-09 06:40:30	0.00	sp73083985699	\N
29	46	1889-08-07	25.204819	55.270931		male	0	0.00	2024-06-12 08:40:11	2024-08-09 06:41:16	0.00	sp21771969210	\N
34	51	2024-06-11	25.204819	55.270931		male	0	0.00	2024-06-12 21:45:00	2024-08-09 06:42:05	0.00	sp99614086711	\N
39	56	2003-06-12	25.204819	55.270931		male	0	24500.00	2024-06-13 14:38:26	2024-08-09 06:42:31	0.00	sp43077293709	\N
50	67	1997-06-13	25.204819	55.270931		male	1	0.00	2024-06-13 20:39:13	2024-08-09 06:43:09	0.00	sp46857587081	\N
64	86	2024-06-14	25.204819	55.270931		male	0	0.00	2024-06-15 15:07:33	2024-08-09 06:43:59	0.00	sp35754971402	\N
79	110	\N	25.204819	55.270931		male	0	0.00	2024-08-14 13:24:41	2024-08-14 18:18:28	0.00	sp89860425020	\N
82	125	2005-08-04	25.204819	55.270931		male	0	0.00	2024-08-15 08:54:13	2024-08-15 11:08:26	0.00	sp94889179348	\N
83	130	2024-08-01	25.204819	55.270931		male	0	0.00	2024-08-16 09:49:04	2024-08-16 09:49:04	0.00	sp17481611501	\N
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: favourites; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.favourites (id, vendor_id, customer_id, created_at, updated_at) FROM stdin;
6	25	30	2024-06-11 17:10:58	2024-06-11 17:10:58
9	2	30	2024-06-11 19:07:58	2024-06-11 19:07:58
17	2	29	2024-06-12 10:35:34	2024-06-12 10:35:34
21	7	48	2024-06-12 20:49:11	2024-06-12 20:49:11
22	27	50	2024-06-12 22:27:47	2024-06-12 22:27:47
24	27	30	2024-06-13 00:11:26	2024-06-13 00:11:26
25	27	29	2024-06-14 00:24:22	2024-06-14 00:24:22
26	10	86	2024-06-15 15:08:00	2024-06-15 15:08:00
28	27	92	2024-06-17 11:07:46	2024-06-17 11:07:46
30	10	92	2024-06-19 00:20:12	2024-06-19 00:20:12
31	27	82	2024-06-20 10:42:06	2024-06-20 10:42:06
33	25	92	2024-06-21 12:48:12	2024-06-21 12:48:12
34	27	100	2024-06-21 19:21:26	2024-06-21 19:21:26
35	7	100	2024-06-22 01:37:41	2024-06-22 01:37:41
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2024_03_21_122103_add_indexes_to_tables	1
5	2024_03_22_111410_create_country_models_table	1
6	2024_03_22_190801_add_category_table	1
7	2024_04_03_174246_add_vendor_details_table	1
8	2024_04_08_160714_add_customer_user_details_table	1
9	2024_04_24_155243_add_vendor_portfolio_table	2
10	2024_04_25_020535_modify_vendor_user_detail_table	2
11	2024_04_25_030125_add_vendor_booking_table	2
12	2024_05_01_104409_add_article_table	3
13	2024_05_02_051918_add_vendor_bookings_dates	4
14	2024_05_02_051920_create_users_role_table	5
15	2024_05_02_051921_create_role_permissions_table	5
16	2024_05_02_153917_add_role_table	5
17	2024_05_02_153918_add_role_id_to_users_table	5
18	2024_05_03_052938_add_all_booking_order_tables	5
19	2024_05_04_220436_add_vendor_ratings_table	6
20	2024_05_17_003006_add_fields_vendor_user_details_table	7
21	2024_05_17_201339_alter_vendor_booking_table	8
22	2024_05_17_201340_vendor_booking_media_table	8
23	2024_05_17_201350_add_customer_ratings_table	8
24	2024_05_20_111718_create_personal_access_tokens_table	9
25	2024_05_20_111720_add_temp_user_table	9
26	2024_05_20_120938_add_fields_user_table	9
27	2024_05_21_025742_add_booking_field_vendor_table	9
28	2024_05_04_220438_add_app_banner	10
29	2024_05_21_025750_create_settings	11
30	2024_06_07_062909_add_temp_transactions_table	12
31	2024_06_07_141805_add_booking_resource_table	13
32	2024_06_11_081804_add_add_to_favourite_table	14
33	2024_06_11_081845_add_contact_us_entries	15
34	2024_07_05_092855_last_login	16
35	2024_07_05_150417_categories	17
36	2024_07_06_085307_disraption	18
37	2024_07_23_123316_add_fields_to_customer_table	19
38	2024_07_31_085148_add_fields_vendor_booking_table	20
39	2024_08_13_224050_add_duration_field_vendor_bookings_table	21
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
1	App\\Models\\User	9	api	f006db4d0a3184276b4de3e79ef41b9021593aa2aef6caa1e967b7782a465ebd	["*"]	\N	\N	2024-05-22 08:06:18	2024-05-22 08:06:18
2	App\\Models\\User	9	api	e818159b313a59e519e7c000a58e51dbf424a98224846aa08aad0b2b0546e686	["*"]	\N	\N	2024-05-22 16:30:29	2024-05-22 16:30:29
3	App\\Models\\User	9	api	dcc063cb19160a4d7e082da640abdbe71af9405cee0b12e6ab7ff41fe2ef9057	["*"]	\N	\N	2024-05-22 16:30:37	2024-05-22 16:30:37
4	App\\Models\\User	6	api	a0377d9ffe2ffe49ce105f11d1484f560f01654e7a1615755e762473305a7d71	["*"]	\N	\N	2024-05-22 16:50:24	2024-05-22 16:50:24
5	App\\Models\\User	4	api	eafc904ec602655f446a33b0541b37d6481b540c0e9bd6750ce8a8d3f9491949	["*"]	\N	\N	2024-05-22 16:51:16	2024-05-22 16:51:16
6	App\\Models\\User	3	api	58c6891eee52f724874f68e33c031af3f84f86c1faa694c8d8399c616a5ef9b7	["*"]	\N	\N	2024-05-22 16:53:49	2024-05-22 16:53:49
7	App\\Models\\User	3	api	565c6cdb8438b9674fc3ba4f09689a1821725b5683f907b56c694bd091da40f8	["*"]	\N	\N	2024-05-22 16:55:13	2024-05-22 16:55:13
8	App\\Models\\User	3	api	32b967235e422405456671f2c65dbf6923622abdfc447626f484e44b6859cdf5	["*"]	\N	\N	2024-05-22 16:55:38	2024-05-22 16:55:38
9	App\\Models\\User	9	api	d7ab6ae9c40953da1c1c24148d53662e0ff61f0ae34e42521aa20ad730f9856b	["*"]	\N	\N	2024-05-23 18:38:03	2024-05-23 18:38:03
10	App\\Models\\User	6	api	6212b2839172d7b517ae1573130f97f8b715d33406530d8660f71e1665f7c04f	["*"]	\N	\N	2024-05-23 18:38:05	2024-05-23 18:38:05
11	App\\Models\\User	9	api	550fd1bb569e01d7a7fc88ceed8963c42ffe84f0ac9ca7db9f7a8cac75e0e1df	["*"]	\N	\N	2024-05-23 18:42:31	2024-05-23 18:42:31
12	App\\Models\\User	12	api	9d65d057c550dc9e69e740249faaf92b1656a0ca908ee7302c9ba15a606d2e42	["*"]	\N	\N	2024-05-24 05:59:47	2024-05-24 05:59:47
13	App\\Models\\User	12	api	2d1b360bf90e1c2fdcce4da03d31ac72da5deab62cb465755834fd465f5242d2	["*"]	\N	\N	2024-05-24 06:00:08	2024-05-24 06:00:08
14	App\\Models\\User	13	api	fd40acead1a2f1eb8eaf44987c8c86b0d3296be893e8f814f7ae4b55c4a3c723	["*"]	\N	\N	2024-05-24 11:42:34	2024-05-24 11:42:34
15	App\\Models\\User	14	api	cc3017e8d13c71e02354766c2b68a6783962cd73ca77ce7294747eea6db635c7	["*"]	\N	\N	2024-05-25 07:53:27	2024-05-25 07:53:27
16	App\\Models\\User	14	api	3c322506aea8ed87dfc95174a5a91db99fa7b556d9fbd93efd631279e2823b6f	["*"]	\N	\N	2024-05-25 07:54:10	2024-05-25 07:54:10
17	App\\Models\\User	14	api	68397f7eb842a01a46929d8ffee8e65169beee70dd90bdc2ae16f4d2213c2b46	["*"]	\N	\N	2024-05-25 09:36:47	2024-05-25 09:36:47
18	App\\Models\\User	14	api	3d642f4441e7cd3bcc99cf325c060e081a9fc02405ad382f1258f9d947359750	["*"]	\N	\N	2024-05-25 09:52:40	2024-05-25 09:52:40
19	App\\Models\\User	14	api	4de52e7dbd8b352f0e8135411e83c1ed79a26a9a072ec4d1a156d18b9935da30	["*"]	\N	\N	2024-05-25 11:15:50	2024-05-25 11:15:50
20	App\\Models\\User	14	api	c7ec05577f214aed040985c610120d95e70f92f5dd92d52bfe78c664e0131140	["*"]	\N	\N	2024-05-25 11:17:29	2024-05-25 11:17:29
21	App\\Models\\User	14	api	57030f76eb5f4492fa1f0c4a408a9312c140a489aaf89de44ad723e65a2869a9	["*"]	\N	\N	2024-05-25 11:22:28	2024-05-25 11:22:28
22	App\\Models\\User	14	api	751d79eea71580b3390783e0d67466d7b380ed658ace1014214d5e6c3875b9cd	["*"]	\N	\N	2024-05-25 11:35:49	2024-05-25 11:35:49
23	App\\Models\\User	15	api	bac37e3305d493e2f77b1938d0e44b6c543a90b70f90d575621210f4c7acac2f	["*"]	\N	\N	2024-06-03 17:56:06	2024-06-03 17:56:06
24	App\\Models\\User	15	api	f448ce9fbc7dc75d54df467c233a6458cb64ca8de6d3be2326f41e8cbddb9714	["*"]	\N	\N	2024-06-04 11:36:24	2024-06-04 11:36:24
25	App\\Models\\User	15	api	8701c8a6a748f5a938be91a604b0fe0c9e3cd86694ac91fdec7ee3754d3429b0	["*"]	\N	\N	2024-06-04 11:40:01	2024-06-04 11:40:01
28	App\\Models\\User	15	api	ddb94b24273b347212f7ee39dc2d8ae9fb8353cde92378c647cd1d8acbf41086	["*"]	2024-06-04 14:28:23	\N	2024-06-04 14:27:01	2024-06-04 14:28:23
29	App\\Models\\User	15	api	250120c17644de63654a2008066f79597ce657e41bcffa02c9032f219b08131c	["*"]	2024-06-04 14:58:19	\N	2024-06-04 14:57:41	2024-06-04 14:58:19
30	App\\Models\\User	9	api	124fdd44535d6581ffdb1883657cf1e8d9430912d1d952c558ffb74b2357a420	["*"]	\N	\N	2024-06-04 15:01:23	2024-06-04 15:01:23
31	App\\Models\\User	9	api	3a81a68bc796ea4d1bb3ccb53977178466c779dde2ccad6e19c5fde89ca43e4f	["*"]	2024-06-04 15:02:17	\N	2024-06-04 15:01:49	2024-06-04 15:02:17
34	App\\Models\\User	9	api	e38ecf7bc0debba235284055bb4c88d7157abf84a4131e70eecb4b042693613f	["*"]	2024-06-06 14:16:42	\N	2024-06-04 15:41:22	2024-06-06 14:16:42
351	App\\Models\\User	30	api	77b1819e62d6ed3c5843da7bac7dee1e7ed52ad66077803b15354b5c0e1b4db8	["*"]	\N	\N	2024-06-14 11:27:58	2024-06-14 11:27:58
37	App\\Models\\User	16	api	3503fabef3d62aeb6a076365cac2a7f2c618a41bdecee75c8b706cf476385e9e	["*"]	2024-06-06 18:49:15	\N	2024-06-05 15:30:28	2024-06-06 18:49:15
35	App\\Models\\User	9	api	c992ec5d21d1df9f8f2bcc5b870e87220c0a971d76dd7f889d81ab5d025e243c	["*"]	2024-06-05 15:08:32	\N	2024-06-05 14:42:47	2024-06-05 15:08:32
32	App\\Models\\User	15	api	1d2c115613bc9995ba463eebbebd2f5d8fae859e75c48770d7c48cff81d30910	["*"]	2024-06-04 15:35:57	\N	2024-06-04 15:32:47	2024-06-04 15:35:57
36	App\\Models\\User	16	api	b7776316e40ad9faca9bee2384d6c3030fa167de16fd13e7c1c59af74cbdf6af	["*"]	\N	\N	2024-06-05 15:27:16	2024-06-05 15:27:16
27	App\\Models\\User	15	api	318eab9bcc263c56cd1a75744d3994d74c3cf83ac3ea428162003b9f3b891bbc	["*"]	2024-06-04 15:40:29	\N	2024-06-04 13:38:15	2024-06-04 15:40:29
26	App\\Models\\User	15	api	27f89068867ee83a494bd07ad18a16f72c0a5e2fbd0a9c04fcf91d2cfc603ff3	["*"]	2024-06-05 14:42:00	\N	2024-06-04 11:57:28	2024-06-05 14:42:00
33	App\\Models\\User	9	api	120f2a4b20b59690aa203429c07d639acb18f0f15deb101fbcde44ac57a5e901	["*"]	2024-06-06 12:20:14	\N	2024-06-04 15:39:03	2024-06-06 12:20:14
38	App\\Models\\User	9	api	5f825d37927e95e581e17836bd3a213eacfe523c633563f829cc6a6b7c8dffa5	["*"]	\N	\N	2024-06-05 17:32:03	2024-06-05 17:32:03
355	App\\Models\\User	30	api	d1192e41aa49a44ee9fba07f58432e693cba28c352add67f031971e9e07a10ed	["*"]	2024-06-14 14:42:27	\N	2024-06-14 14:27:29	2024-06-14 14:42:27
39	App\\Models\\User	26	api	ee34c5c1ca9f173da0200933f3e7b4375309b6b19a865ae3fb3e344e31bfc9a8	["*"]	\N	\N	2024-06-06 09:36:56	2024-06-06 09:36:56
40	App\\Models\\User	26	api	204a51db31c65ce1cb715a62168a28965b1254f51aa4696e885768944cb6717e	["*"]	\N	\N	2024-06-06 09:37:39	2024-06-06 09:37:39
41	App\\Models\\User	26	api	39d04e422dfdb6eb9586e39acda31d58d0e3cacdd5543ad171780da96a16bcdb	["*"]	\N	\N	2024-06-06 09:46:56	2024-06-06 09:46:56
42	App\\Models\\User	26	api	3788322356a64100288a6c5bcbd70c1407f5aaa701303dc61f361d8580591ad3	["*"]	\N	\N	2024-06-06 09:52:35	2024-06-06 09:52:35
43	App\\Models\\User	28	api	8cd67e47ba88449419592481c96e5820d99cae50d45a7905777bf4aa24a82604	["*"]	\N	\N	2024-06-06 10:32:14	2024-06-06 10:32:14
44	App\\Models\\User	28	api	d27791dc2a47d33f2f02e2a9d7ac9b222efeb21d8e9ee672e0fb2f4d8ef943d3	["*"]	\N	\N	2024-06-06 10:39:37	2024-06-06 10:39:37
211	App\\Models\\User	47	api	2ea208eac639519998607595dbf4c154edbe81b05778b76deee368c9b2082b18	["*"]	\N	\N	2024-06-12 13:47:03	2024-06-12 13:47:03
45	App\\Models\\User	28	api	ddd78f86648444a00e544c489c986e9c1d8d4c2f14051ea0a350d0bcf00f0c36	["*"]	\N	\N	2024-06-06 11:01:54	2024-06-06 11:01:54
46	App\\Models\\User	9	api	be9f017d2a1b500257f8105bdfbbc5119fefc2b70768ac4b673e0e37bf5e8fb9	["*"]	\N	\N	2024-06-06 11:58:23	2024-06-06 11:58:23
47	App\\Models\\User	29	api	ce32371ab33d9f36fc20df3132c43bbfa9f2e2d521840c4e9d893cc56b7b065e	["*"]	\N	\N	2024-06-06 12:31:52	2024-06-06 12:31:52
48	App\\Models\\User	4	api	2448081c0f8790c30f28da00614970d3d68ab268c071e0921389d1eb692f5022	["*"]	\N	\N	2024-06-06 12:34:33	2024-06-06 12:34:33
352	App\\Models\\User	54	api	f8e8fb4996366de84adf91d5a5c269ef3b550910ab8a7686c5c24bfc2e862069	["*"]	\N	\N	2024-06-14 12:17:02	2024-06-14 12:17:02
50	App\\Models\\User	4	api	9cae851aca7c5966fa27b6a922ec8aeed49bc93d76d506960824d18bf9187e9c	["*"]	2024-06-06 13:17:38	\N	2024-06-06 13:16:58	2024-06-06 13:17:38
54	App\\Models\\User	9	api	2b05d2fb23433271853469978949791b68196d70513e6bab9afdd594a9f3b6d2	["*"]	2024-06-06 18:18:49	\N	2024-06-06 15:15:06	2024-06-06 18:18:49
73	App\\Models\\User	31	api	cbbb316d9138e3e092c2529e9388d705707d008c269a38aa79d87f23f7f9398c	["*"]	\N	\N	2024-06-07 13:30:47	2024-06-07 13:30:47
78	App\\Models\\User	9	api	08292e2ca79bda9399c0515a026881c3cea6e8ab4a27266a4faa1097ac425da6	["*"]	2024-06-10 13:27:16	\N	2024-06-07 15:19:00	2024-06-10 13:27:16
65	App\\Models\\User	30	api	19bf6382a01199275cd64072fe84264a83e016322f39844e716045c087c43e61	["*"]	2024-06-06 22:16:43	\N	2024-06-06 22:16:19	2024-06-06 22:16:43
52	App\\Models\\User	9	api	0e05a87342a611dec00fcc3eaf9b780cc0716b9b20ff1f47ddeb3da161a32c17	["*"]	\N	\N	2024-06-06 15:12:02	2024-06-06 15:12:02
53	App\\Models\\User	15	api	82e0ca8bba3c8b18395985dee3c5f7395711356ea6d3fe6f06347d503c7e8fef	["*"]	2024-06-06 15:14:16	\N	2024-06-06 15:14:07	2024-06-06 15:14:16
89	App\\Models\\User	31	api	8d37eaa27fb66f9fafdfab7147ecd16f7cd8a73f928f24828f595d5c620ceb31	["*"]	2024-06-11 21:05:21	\N	2024-06-08 01:44:58	2024-06-11 21:05:21
56	App\\Models\\User	28	api	ab15ed8fb72856a661b8edf07de67cdbd9fa4dc65fad29c148d3af17af833ac4	["*"]	2024-06-06 18:33:11	\N	2024-06-06 18:01:22	2024-06-06 18:33:11
55	App\\Models\\User	30	api	f49d9cda36940244a38254ed8e55aa20d67845ac829a57e90b64bb7d2d76090c	["*"]	\N	\N	2024-06-06 17:53:26	2024-06-06 17:53:26
77	App\\Models\\User	31	api	fbff2dda616e7a427bec2a85b8ea14c24ddb7154a38f1f07f05d3bcf44c27b57	["*"]	2024-06-07 15:04:17	\N	2024-06-07 15:04:06	2024-06-07 15:04:17
63	App\\Models\\User	30	api	4bc8f15f1e5685ec80a12afdebb8b92ba205d582493ed6881da1fe4d0aace6e9	["*"]	2024-06-07 13:36:19	\N	2024-06-06 20:40:09	2024-06-07 13:36:19
59	App\\Models\\User	9	api	8adb7af4e888fe81919e7fc30644eee6768e254e538825bfb9144dfba40d6402	["*"]	2024-06-06 18:50:53	\N	2024-06-06 18:43:57	2024-06-06 18:50:53
67	App\\Models\\User	9	api	bac61aa221c0f6bd38c0b4b5ba14c5832c88007177d2a129237abf951c234fbe	["*"]	2024-06-11 10:38:23	\N	2024-06-06 22:30:07	2024-06-11 10:38:23
74	App\\Models\\User	30	api	5c8eedf17ba3f02ab7b0465b6cffa18bb3acd21504b73cbe0ef1e8df4ee85393	["*"]	2024-06-10 12:23:37	\N	2024-06-07 13:57:31	2024-06-10 12:23:37
66	App\\Models\\User	9	api	b8949bd7d8b55333df73fb341575e415be0521072163e71c88ca019099e48c45	["*"]	2024-06-06 23:07:56	\N	2024-06-06 22:18:09	2024-06-06 23:07:56
58	App\\Models\\User	28	api	9db4ff405a9d698fcc55f127480d9d9aa0a791b70aff63393fa90b25ed2b9ece	["*"]	2024-06-06 18:36:14	\N	2024-06-06 18:33:42	2024-06-06 18:36:14
57	App\\Models\\User	28	api	31f50133015e1fd60b666d5f846b98d638527eab670ae0a54845779656397e97	["*"]	2024-06-06 18:39:39	\N	2024-06-06 18:25:20	2024-06-06 18:39:39
70	App\\Models\\User	30	api	ea2f748e4520b189b1d60b0352bcc2a69a126af9855c7c7d8a0c91fb56f4215a	["*"]	2024-06-10 17:38:31	\N	2024-06-07 13:21:02	2024-06-10 17:38:31
61	App\\Models\\User	30	api	ac4ad255fdce2902d339959715e285bcb59def96273ac40b1507662b524326a1	["*"]	2024-06-06 19:26:41	\N	2024-06-06 19:03:23	2024-06-06 19:26:41
192	App\\Models\\User	45	api	8db90ef9991d429015cc8a74bac35f3ce3f56ac5ae9686729d2d6cbb57800cd9	["*"]	2024-06-12 08:00:13	\N	2024-06-12 06:42:26	2024-06-12 08:00:13
51	App\\Models\\User	9	api	3d6f0c560df4cfe94f385c04c312ba4af3b64c0d71857256f39e6c1a0d82c6ac	["*"]	2024-06-06 23:02:28	\N	2024-06-06 13:19:06	2024-06-06 23:02:28
81	App\\Models\\User	26	api	c8dd6f1b38fbdd8dec9af975358a49eddbb983d96d0fac4705b6166ac56dfc10	["*"]	\N	\N	2024-06-07 20:05:05	2024-06-07 20:05:05
83	App\\Models\\User	31	api	af1ce77d68f587d2e25fa140ce0dfbd2c16d6265d3ad03e1903fbf4c7e5375cc	["*"]	\N	\N	2024-06-08 00:17:27	2024-06-08 00:17:27
75	App\\Models\\User	28	api	afef2dc56a398fc454d8dc30f5271009003217be7ec34011c006f305ff18e740	["*"]	\N	\N	2024-06-07 13:59:37	2024-06-07 13:59:37
60	App\\Models\\User	30	api	2c68a7c1ccb06d84906e92e12060a906e1e646660ddc980a947577d1311b0e88	["*"]	2024-06-07 11:30:46	\N	2024-06-06 19:00:36	2024-06-07 11:30:46
72	App\\Models\\User	31	api	89d9210f6692a307da62608bbbebcd9101c429831cf11bcf45f6351f9f8af904	["*"]	2024-06-07 13:26:13	\N	2024-06-07 13:25:51	2024-06-07 13:26:13
62	App\\Models\\User	30	api	e699b54ce58ee92b726788cecb5a15e1816b8924781ea36331e1233f7e875c32	["*"]	2024-06-10 19:47:54	\N	2024-06-06 19:27:26	2024-06-10 19:47:54
79	App\\Models\\User	31	api	ba9ee916e7d2af448cda558d23a2860db69e930f456cca01b35003d0e11b2fa0	["*"]	2024-06-08 00:21:10	\N	2024-06-07 15:36:24	2024-06-08 00:21:10
69	App\\Models\\User	9	api	f3b8232b7151414484489dfe55f96047afc4e3177c29cc219c142883f8ad6622	["*"]	2024-06-12 12:50:05	\N	2024-06-07 10:49:50	2024-06-12 12:50:05
80	App\\Models\\User	31	api	13466916531bfc2cba6094b405f633e1fa9e915219093f22a4f529334bdf0026	["*"]	\N	\N	2024-06-07 15:38:00	2024-06-07 15:38:00
76	App\\Models\\User	28	api	b43c729feca2c8a49e0caf07599435f2551a4bd2b2fa3f94c81cc523c5c65ba4	["*"]	2024-06-14 01:49:54	\N	2024-06-07 14:00:00	2024-06-14 01:49:54
86	App\\Models\\User	31	api	9510557bc8843a08cefd6d8e010d4f0bb3d31611a6db3d05833b1a0c7f68598b	["*"]	\N	\N	2024-06-08 00:35:41	2024-06-08 00:35:41
84	App\\Models\\User	31	api	0bd853263c72f504f6f353602faab7cfb156dcec01cffe05c6bd9d25293eb147	["*"]	2024-06-08 00:32:51	\N	2024-06-08 00:32:42	2024-06-08 00:32:51
82	App\\Models\\User	26	api	73fbbccd21feab746f3ee4cbc5c4bfa66a2cb0a6a6799fd9c92abf0e07a806aa	["*"]	2024-06-08 00:23:26	\N	2024-06-07 20:05:09	2024-06-08 00:23:26
87	App\\Models\\User	31	api	82d34f1b07577193c78aa35bb982fb62c1724333ed5f33d60d97de6315888b98	["*"]	\N	\N	2024-06-08 01:41:49	2024-06-08 01:41:49
88	App\\Models\\User	31	api	fbe71e6af8e574e85bb6368d42effb91f050670470ecf628a6b4deea37394495	["*"]	\N	\N	2024-06-08 01:44:29	2024-06-08 01:44:29
90	App\\Models\\User	31	api	57beeb5245fd1995c09982f57aff4908a3a8b3fd3fe778a995a30508b47eb463	["*"]	\N	\N	2024-06-08 02:46:50	2024-06-08 02:46:50
91	App\\Models\\User	31	api	5354493a759319e9e5c30d17503ab53116f433dee6a42600f9a6b60dedcd690f	["*"]	\N	\N	2024-06-08 03:52:05	2024-06-08 03:52:05
92	App\\Models\\User	31	api	1b57d94a36c87829ee3fe24214e033d03a254baa658ffbfe2b2ff934fb224360	["*"]	\N	\N	2024-06-08 03:52:26	2024-06-08 03:52:26
93	App\\Models\\User	31	api	4470e57d639ea45a08d3878b7ebc7fbd24dd91d906ba7c45a0f761380a443278	["*"]	\N	\N	2024-06-08 03:53:22	2024-06-08 03:53:22
212	App\\Models\\User	47	api	a945f12bbd232dbf4e955f2683aa35ac90f3b24cda3ad76d8ea5de1fb35db1b8	["*"]	\N	\N	2024-06-12 13:47:47	2024-06-12 13:47:47
94	App\\Models\\User	4	api	83f8e921ea2b4ae3acb4795fb3ceea9e1dcf457ec6b305546b76e4349a299c63	["*"]	\N	\N	2024-06-10 11:43:31	2024-06-10 11:43:31
193	App\\Models\\User	45	api	6ec4ff667c4d11a9451134336e04de571f5b35d8cc5dbf7cb7bf37cda5324f52	["*"]	\N	\N	2024-06-12 06:42:55	2024-06-12 06:42:55
248	App\\Models\\User	50	api	d1905c8ebf09de100cb8a9d4281463ea11a582b8552835e8918108fe99beda0b	["*"]	\N	\N	2024-06-13 08:30:27	2024-06-13 08:30:27
97	App\\Models\\User	9	api	1ff687c114f653aebf59cab5746c37db5fcdac4d7c71e9eee2e62ced802b661f	["*"]	\N	\N	2024-06-10 13:30:17	2024-06-10 13:30:17
216	App\\Models\\User	30	api	89fc2a4889a46684aa3dee51e2253379c58267ff0cc71e12c6aedf30f53ae4d2	["*"]	2024-07-15 09:36:31	\N	2024-06-12 16:23:01	2024-07-15 09:36:31
229	App\\Models\\User	50	api	17c6ba442d388f372ed76dd637777ecadb39ecd50f9812769dcd4bd091c92069	["*"]	2024-06-12 22:33:07	\N	2024-06-12 21:42:59	2024-06-12 22:33:07
213	App\\Models\\User	47	api	5d1f41886e85c139317d5fc6bd9938847a45d6459cb4397a740ac8a895342bfb	["*"]	\N	\N	2024-06-12 13:47:56	2024-06-12 13:47:56
280	App\\Models\\User	63	api	bd4af8db4cda0949e2c33f4a76f63dc059878ac0c2ebc412d4d952d9b164fd04	["*"]	2024-06-13 14:56:46	\N	2024-06-13 14:56:36	2024-06-13 14:56:46
215	App\\Models\\User	47	api	31cab1da0c2299a1c63f5714c7fbcca7a75145ba84b519200eea5c581030431c	["*"]	\N	\N	2024-06-12 13:50:21	2024-06-12 13:50:21
221	App\\Models\\User	15	api	c339871d78f4f54f39008de52cb7f7632f322a1ba468b349da6110e8d8a5d058	["*"]	2024-06-13 11:23:34	\N	2024-06-12 19:51:29	2024-06-13 11:23:34
282	App\\Models\\User	64	api	17d52b60f81e3352e43eddd4a3c0cc890f7022ed344642aa83c8a837135a6032	["*"]	\N	\N	2024-06-13 14:58:10	2024-06-13 14:58:10
239	App\\Models\\User	50	api	113b1077d44536884177d8c69ac39c298918b08335e3422c728cf098739ee32e	["*"]	2024-06-12 22:36:16	\N	2024-06-12 22:34:47	2024-06-12 22:36:16
258	App\\Models\\User	54	api	786b70e71ae464f24f4690d9c4f0cf2bbcf9549193b9b0f4d430d86cb6cb929b	["*"]	2024-06-13 11:25:29	\N	2024-06-13 11:15:07	2024-06-13 11:25:29
98	App\\Models\\User	9	api	f94d5a31de718e6ce84b8baaae1bcfba53cd8673e10dde0343f92743235ec662	["*"]	2024-06-10 13:56:54	\N	2024-06-10 13:30:31	2024-06-10 13:56:54
262	App\\Models\\User	15	api	a542f01e21ea30b25c7ad5fca91f7fe6eb2a01e2b099bd60fac23b78113f93db	["*"]	\N	\N	2024-06-13 11:51:12	2024-06-13 11:51:12
217	App\\Models\\User	46	api	f21e6bbe2e7c9a9d102eae121dd5f9722227401977f492b915926f3c68d700cc	["*"]	2024-06-12 17:10:07	\N	2024-06-12 16:52:35	2024-06-12 17:10:07
100	App\\Models\\User	9	api	3862c575d1ee3577bec4dff94a00558d3daffb0c09bacdac2db296329537d3ef	["*"]	\N	\N	2024-06-10 13:57:32	2024-06-10 13:57:32
266	App\\Models\\User	33	api	51d43e9d9ee013aae7586bcf871b09c33e6ebac9b77371de38f015a47032f4e2	["*"]	2024-06-13 13:24:17	\N	2024-06-13 13:23:50	2024-06-13 13:24:17
99	App\\Models\\User	9	api	1ff1bed29f4bc0a30bfea7ead47c8dcc087efef44c08797054ef2308db764e95	["*"]	2024-06-10 14:00:19	\N	2024-06-10 13:57:03	2024-06-10 14:00:19
227	App\\Models\\User	50	api	daaf20987afcc1c47b2c6ffed6a8f48cca601d953a79f37f509bc935fe25fef4	["*"]	\N	\N	2024-06-12 21:41:11	2024-06-12 21:41:11
101	App\\Models\\User	9	api	258998583d5c6dea7ad8f7b542847b123d5b9ecd47434be67e64ff5a75df2d72	["*"]	2024-06-10 14:11:56	\N	2024-06-10 14:00:32	2024-06-10 14:11:56
102	App\\Models\\User	9	api	6c6222e2b3f7dbbb63ea8e77a69e42272ab06d1cb13225ed63da3c5c837cbf06	["*"]	\N	\N	2024-06-10 14:12:14	2024-06-10 14:12:14
103	App\\Models\\User	9	api	2c42d22a1726c87036169463edb69659359760331c20f8220ac3df0b505c6307	["*"]	\N	\N	2024-06-10 14:12:27	2024-06-10 14:12:27
104	App\\Models\\User	9	api	1adbbb950a205453f6f94f60f06e12d40bfe25ca018a3d946b4fb217493f2bcf	["*"]	\N	\N	2024-06-10 14:13:57	2024-06-10 14:13:57
105	App\\Models\\User	9	api	6aaa0c1bf5a523655b7822451fdc894cb5bb7ddc7a524af9445361e52909f921	["*"]	\N	\N	2024-06-10 14:14:23	2024-06-10 14:14:23
106	App\\Models\\User	9	api	74df83f1dc7b3d1410c3ddb6f4c946a17870e7af430920a3dffbc6b826763005	["*"]	\N	\N	2024-06-10 14:15:08	2024-06-10 14:15:08
270	App\\Models\\User	55	api	c8c37851946495fe7d5e40ff99add8b6ae3a4f7332614bb253af321045d9edbd	["*"]	2024-06-13 14:36:35	\N	2024-06-13 14:28:56	2024-06-13 14:36:35
254	App\\Models\\User	33	api	49d118456e3591b0dc53655079a6a2a3a8e6a15eb8758f257894727ff1dbb966	["*"]	\N	\N	2024-06-13 09:44:17	2024-06-13 09:44:17
235	App\\Models\\User	50	api	3812372c0c4176f7759732d95257301f72d45617f3ff74a7a9dc9cc597a7b7d2	["*"]	\N	\N	2024-06-12 22:29:59	2024-06-12 22:29:59
245	App\\Models\\User	33	api	3b07f359edc3f7a7c33e1241cd35955a05305a9ccca8b144514f0b828bac0c59	["*"]	\N	\N	2024-06-13 02:06:06	2024-06-13 02:06:06
288	App\\Models\\User	54	api	dfb14a7fab4b2d4593ab32b95f5124efe3d527e3fb1cc5dd6961e9f3c512ebab	["*"]	\N	\N	2024-06-13 16:34:10	2024-06-13 16:34:10
237	App\\Models\\User	50	api	c1aee0c066a5ae772212d52e18679072496c5cafd43517e34c4bac3cf143818b	["*"]	\N	\N	2024-06-12 22:31:01	2024-06-12 22:31:01
290	App\\Models\\User	66	api	1ad9c8b39342b7d31e11492083f7a8246c6bc3f972e2736202198778f0c416d0	["*"]	2024-06-13 16:39:47	\N	2024-06-13 16:38:12	2024-06-13 16:39:47
272	App\\Models\\User	57	api	52c6a1db2b731cfa8ec2b661614f4bd6cbb765043d2e740ecdaebc0492597ec4	["*"]	2024-06-13 14:42:46	\N	2024-06-13 14:39:41	2024-06-13 14:42:46
274	App\\Models\\User	57	api	c821e245eed7fbadcfcd83de34cb5dc336b016ba72f958506f5fa8b152068fcd	["*"]	\N	\N	2024-06-13 14:43:13	2024-06-13 14:43:13
304	App\\Models\\User	69	api	47bcf2ba758eee231240ee54869440cad47fad142de71501c54455b84d31f7b8	["*"]	\N	\N	2024-06-13 20:56:48	2024-06-13 20:56:48
296	App\\Models\\User	67	api	3cda5b7fb4b0109e5694ed9a7fa5c672a479c17b4c297869d091263fede5f750	["*"]	\N	\N	2024-06-13 20:47:01	2024-06-13 20:47:01
298	App\\Models\\User	67	api	4556d43447a3fc36eb1fb30cf2f76aad7f633cb4bcf561ce6509f5b687bfe29c	["*"]	\N	\N	2024-06-13 20:49:00	2024-06-13 20:49:00
300	App\\Models\\User	67	api	e06eb55849558b2b776fd11a83fc1491c27fb92a67e87035d57c0cecea34467e	["*"]	\N	\N	2024-06-13 20:51:20	2024-06-13 20:51:20
302	App\\Models\\User	69	api	860d859e87c0702905579256a72a6bcb854879b393178e5b27047396916c4d0f	["*"]	\N	\N	2024-06-13 20:53:10	2024-06-13 20:53:10
310	App\\Models\\User	68	api	a24733ee6ca2b99798699798df15aaa5e6efc1b1fbff3d5db5f934f7460885b9	["*"]	\N	\N	2024-06-14 02:09:22	2024-06-14 02:09:22
312	App\\Models\\User	68	api	4ad01ce39641a77368e07599ebdbbe5eea0f4e9c6b7bf4269eb31a39b01bf1e9	["*"]	\N	\N	2024-06-14 02:12:36	2024-06-14 02:12:36
314	App\\Models\\User	68	api	ea123527c06ad6eefd3d03c912dd78dbff1e276a4455e2de28ce45cbd02dd7ff	["*"]	\N	\N	2024-06-14 02:16:43	2024-06-14 02:16:43
316	App\\Models\\User	68	api	73edf1dae925dffa47bf7ed452a8b421847677684cd19d45daf9abaa885844ad	["*"]	2024-06-14 02:29:19	\N	2024-06-14 02:27:25	2024-06-14 02:29:19
318	App\\Models\\User	68	api	0e0c7c17fdddac603a8857ecb0d7984a498d78b74077c232b0718659064f8371	["*"]	\N	\N	2024-06-14 02:29:43	2024-06-14 02:29:43
107	App\\Models\\User	9	api	5ee22e91d74c74ce0762b2c6325bc1a41a2f434c09a20ffd04206c846d8de9eb	["*"]	\N	\N	2024-06-10 14:16:01	2024-06-10 14:16:01
198	App\\Models\\User	48	api	89c64af651a202ffbf27d6ed391ce493fdd9768cd454d8e366e96e6cbfec89ed	["*"]	\N	\N	2024-06-12 10:18:23	2024-06-12 10:18:23
108	App\\Models\\User	9	api	3e02548084a349e9bef57bfb84e9d2218f3ce93819e1865c043865ceae34d9cc	["*"]	2024-06-10 14:23:56	\N	2024-06-10 14:17:36	2024-06-10 14:23:56
202	App\\Models\\User	15	api	ffbb3eda467fc0d90593db8d1be472e56286f694e6308c6227786879a172f413	["*"]	\N	\N	2024-06-12 11:25:34	2024-06-12 11:25:34
143	App\\Models\\User	9	api	bce83a9932465ed44946ff3fcc421791b08d548bc2d1320d8dd5ee67c998f853	["*"]	\N	\N	2024-06-11 10:44:00	2024-06-11 10:44:00
206	App\\Models\\User	30	api	a1633741205b0e49a7a76ec84823a719901c92b8de43453f4498d5a84cd7e7cd	["*"]	\N	\N	2024-06-12 12:32:10	2024-06-12 12:32:10
209	App\\Models\\User	47	api	43b4a468a5b7efd238253ea8c354c0e93356f493bd9925370bec9863fcc283c0	["*"]	\N	\N	2024-06-12 13:46:12	2024-06-12 13:46:12
114	App\\Models\\User	32	api	a4974b0e7141aaadcaebfe9cc5af92001361a5e2146f7ed01375e68bd50be6e0	["*"]	\N	\N	2024-06-10 15:18:14	2024-06-10 15:18:14
110	App\\Models\\User	9	api	7dc1d10dd63d190f5df81b3254100df99efc77f692a2ecf388851a7c02129f60	["*"]	2024-06-10 16:01:21	\N	2024-06-10 14:31:45	2024-06-10 16:01:21
158	App\\Models\\User	9	api	4b36e8b754bf95ec9c006ea26d2eb30cacb26c023a9bbca974c41ea25ed675b9	["*"]	\N	\N	2024-06-11 13:35:18	2024-06-11 13:35:18
144	App\\Models\\User	9	api	6ad04653850519d4cb3969dd41c6333ca483e926be4b0d77e4469e17b16a473c	["*"]	\N	\N	2024-06-11 10:45:42	2024-06-11 10:45:42
109	App\\Models\\User	9	api	1f6bd1ab2ae5c8b734fd5a66257a4cfebbad2af9cc3da792e289abb0653b2b92	["*"]	2024-06-10 14:31:36	\N	2024-06-10 14:24:10	2024-06-10 14:31:36
164	App\\Models\\User	15	api	17abbd42a2f28e4a92d3de7ada59457520771fd5c45503e71ed4a27e31f40f1a	["*"]	\N	\N	2024-06-11 14:49:42	2024-06-11 14:49:42
150	App\\Models\\User	35	api	9b95f9fa83ff188091be5b15cec104d4eb76dd8fafdb8b0d9747fe6ae182fc4a	["*"]	\N	\N	2024-06-11 12:57:16	2024-06-11 12:57:16
159	App\\Models\\User	40	api	6723c4c76223716b9436950d9649c1fcccfbb4d7d0c8f6acac41bb0092390203	["*"]	\N	\N	2024-06-11 13:37:53	2024-06-11 13:37:53
120	App\\Models\\User	30	api	a5142cbf9553a8052e68c2c81db7ea3d72b1810db9cbf86df2940c736ed705e5	["*"]	\N	\N	2024-06-10 19:48:33	2024-06-10 19:48:33
145	App\\Models\\User	32	api	e9d3c146279fbd113c0d922e345844f431896872948c5fa735ea6879471e1979	["*"]	2024-06-11 10:56:14	\N	2024-06-11 10:56:13	2024-06-11 10:56:14
137	App\\Models\\User	33	api	3ceb0fd413832f34b0130514e582fe3bf832e0d812e3d4c910d3f48bfd5e732c	["*"]	2024-06-11 09:22:07	\N	2024-06-11 09:12:47	2024-06-11 09:22:07
132	App\\Models\\User	34	api	fb7204f6ace7f0c390f191b1e21f8e0af5ef71012ae866eaad113d18292d0893	["*"]	\N	\N	2024-06-10 21:25:30	2024-06-10 21:25:30
165	App\\Models\\User	15	api	4be3da8608cb1e922e2a6b40fe5c6565d702d28d5bc1dd82cf064b3241036907	["*"]	\N	\N	2024-06-11 14:51:11	2024-06-11 14:51:11
133	App\\Models\\User	30	api	7429ea549a730c94f8fb0fdda813cd615e52192a082e1c0bcf28622216ffc0bd	["*"]	\N	\N	2024-06-10 21:52:19	2024-06-10 21:52:19
134	App\\Models\\User	30	api	ebb990092de5f4e6cacdf656def2416a884e444b714275fcd64681f9833cdb2b	["*"]	\N	\N	2024-06-10 21:52:38	2024-06-10 21:52:38
173	App\\Models\\User	43	api	a79b9860a1e97db456e2f94b0b3d322fb2f07c5c5e85435218dc958b0839f3e5	["*"]	\N	\N	2024-06-11 17:25:21	2024-06-11 17:25:21
167	App\\Models\\User	38	api	e813644e4f38a1bc148cac7c3525f977cdeb8ca9b99db78339d2e036c1dddfd4	["*"]	2024-06-11 15:29:52	\N	2024-06-11 15:28:59	2024-06-11 15:29:52
151	App\\Models\\User	37	api	3b90ff2ab704d6139e45fc10c6b588981d420bd48577614f7c0fb0f4d24e63d5	["*"]	\N	\N	2024-06-11 13:02:35	2024-06-11 13:02:35
152	App\\Models\\User	38	api	af3049f373ad8ee3c19a6d35026e6fdfed89e97bcf1eae6540e16e065e739b48	["*"]	\N	\N	2024-06-11 13:09:49	2024-06-11 13:09:49
175	App\\Models\\User	43	api	b1afcb733b7eb470db19019af69fd921cf539639d35cb1e03303a170039176e6	["*"]	2024-06-11 18:22:41	\N	2024-06-11 17:31:26	2024-06-11 18:22:41
142	App\\Models\\User	9	api	4675027ba405ce44cf1c3c275683457baeb7f22082ad8ac58a7f0909d46f32eb	["*"]	\N	\N	2024-06-11 10:43:45	2024-06-11 10:43:45
174	App\\Models\\User	43	api	a5c2f7e7b11a29cb94c6b393b6290d425c217f1c3d29735cd2e86a95c3fc9bea	["*"]	2024-06-11 17:25:45	\N	2024-06-11 17:25:45	2024-06-11 17:25:45
156	App\\Models\\User	39	api	ca803424c35f0cb88f8e58ef8045f15575ab09c5e2f1ab2ea98ca8ed3cb532c2	["*"]	\N	\N	2024-06-11 13:32:06	2024-06-11 13:32:06
148	App\\Models\\User	36	api	d2f7806ff098143c10407034585d87ec98988601a3fb66d5fb3c553eb188677d	["*"]	\N	\N	2024-06-11 12:45:07	2024-06-11 12:45:07
166	App\\Models\\User	35	api	841fc831964a3b4f4586b0687432384a86326d3816766393a6dad1149e4119d8	["*"]	2024-06-11 16:09:01	\N	2024-06-11 15:19:00	2024-06-11 16:09:01
154	App\\Models\\User	35	api	5f9330007ce3cd7d0679250a55f62884575994cc6134aeeb68743e8a4ce37ed5	["*"]	2024-06-11 13:39:59	\N	2024-06-11 13:30:08	2024-06-11 13:39:59
160	App\\Models\\User	9	api	9e15cb96ce007c02fa044c6f503abe93655889fda7db2dde186d895203bd3ada	["*"]	\N	\N	2024-06-11 13:43:26	2024-06-11 13:43:26
161	App\\Models\\User	9	api	117b451263508c71a212ff3f456734dfe2f7894a21d589cccfc8983bfe50bd8f	["*"]	\N	\N	2024-06-11 13:43:32	2024-06-11 13:43:32
169	App\\Models\\User	4	api	e135e47ef524119444ab7fa708330d41e344757d9a8071c2d0f162fb1b3b9e30	["*"]	\N	\N	2024-06-11 15:59:21	2024-06-11 15:59:21
168	App\\Models\\User	42	api	83456210e4c14d1d20135b5ea6547870b60ee858000e98c59d45ced1091650e6	["*"]	2024-06-11 16:22:59	\N	2024-06-11 15:38:46	2024-06-11 16:22:59
171	App\\Models\\User	42	api	15b869b6ccd10298fa28f14fb757fd7f7d95fc794f6f963d96bade62ebd7744d	["*"]	\N	\N	2024-06-11 16:23:00	2024-06-11 16:23:00
178	App\\Models\\User	33	api	2374487beaf8a99bb6183e90839a6126c54592b3b26bd8de7dc681391732a1f2	["*"]	\N	\N	2024-06-11 19:42:00	2024-06-11 19:42:00
181	App\\Models\\User	44	api	fdfdf3203dc7e0084817664356c131b5a26ef03b33628d679f08cbb8f8af6524	["*"]	2024-06-11 21:22:22	\N	2024-06-11 19:53:12	2024-06-11 21:22:22
179	App\\Models\\User	35	api	4e1ab16697eb801e297e6d119ee711b5e7f8ab5b588c99b4de397708ae0fd2a4	["*"]	2024-06-11 21:16:10	\N	2024-06-11 19:46:16	2024-06-11 21:16:10
180	App\\Models\\User	44	api	da18824c5ebfe27a165ea66130ee06f052f51fe9eb9fa94efbdf03b766b807b2	["*"]	\N	\N	2024-06-11 19:50:52	2024-06-11 19:50:52
183	App\\Models\\User	44	api	83d9c6bea941378c6ef1100deb4ea8525ac138c672d55f738f3d353980bf69b1	["*"]	\N	\N	2024-06-11 20:19:22	2024-06-11 20:19:22
184	App\\Models\\User	35	api	622fa075d47ad659fdd9592defbb483751048c7a4fccab0d36552dc5fc791bb3	["*"]	\N	\N	2024-06-11 21:08:55	2024-06-11 21:08:55
185	App\\Models\\User	35	api	3e19343bc9712ba201624abf75355f8b24e72c9b9f24b67f76b5fcb33bb71ab4	["*"]	\N	\N	2024-06-11 21:12:19	2024-06-11 21:12:19
186	App\\Models\\User	35	api	05bd10d8988c4db677061d0f7d083a917b7a7e43faea2a7786bd6afb854083fa	["*"]	\N	\N	2024-06-11 21:12:28	2024-06-11 21:12:28
187	App\\Models\\User	35	api	2229b5dca90709902e8abac9ac058e7bf531dd70d1b9d02006e6b9c99f8e47c3	["*"]	\N	\N	2024-06-11 21:13:14	2024-06-11 21:13:14
188	App\\Models\\User	35	api	8f6591943b51af79903253178f35d536b0eddda08875fb151ccc568068032511	["*"]	2024-06-11 21:16:20	\N	2024-06-11 21:16:11	2024-06-11 21:16:20
335	App\\Models\\User	15	api	3441881c05598137558b657e302a3badad16bc918d7afdec7f069c0a8205ff18	["*"]	2024-06-15 13:15:57	\N	2024-06-14 10:48:41	2024-06-15 13:15:57
49	App\\Models\\User	4	api	ed2a3d267582c96ba72800b1ac41500aeddd25a6e3b3db387a97d04e2bf0f731	["*"]	2024-06-21 14:50:29	\N	2024-06-06 12:35:33	2024-06-21 14:50:29
265	App\\Models\\User	33	api	28d33b753b98110b8ed06c97370ba09b30b2575da30f67cda917c984b368e7e0	["*"]	2024-06-14 15:42:06	\N	2024-06-13 13:13:28	2024-06-14 15:42:06
353	App\\Models\\User	15	api	e47d69c53389ddf07f049d6976aa0dfc52165749634069a55676accbc3625149	["*"]	2024-06-14 14:03:43	\N	2024-06-14 13:42:57	2024-06-14 14:03:43
303	App\\Models\\User	70	api	3b7c3a5fcf8b23210f43f162ba9215ac2ad51d3f80ba251f527c82b5b44433d4	["*"]	\N	\N	2024-06-13 20:54:43	2024-06-13 20:54:43
305	App\\Models\\User	69	api	c74a5ee53a9b078101abb217ed5acad6d1b3cf1c0599a1d6b6c759a729004a88	["*"]	\N	\N	2024-06-13 20:56:53	2024-06-13 20:56:53
259	App\\Models\\User	54	api	d69c3d24c28d159113ae9361ca95ad2c20d624405f66076dedaf624bef8d8ee7	["*"]	\N	\N	2024-06-13 11:25:26	2024-06-13 11:25:26
236	App\\Models\\User	50	api	25dd6e0f836ebeac1997851f3df1b0be894ebc1d6514afc7cee430e6d2a5baed	["*"]	\N	\N	2024-06-12 22:30:22	2024-06-12 22:30:22
261	App\\Models\\User	54	api	32a8c3ca2cb5a41ac16ddbba506be8e74d6e1a5e5a494023b537f38e10ac2569	["*"]	\N	\N	2024-06-13 11:25:41	2024-06-13 11:25:41
249	App\\Models\\User	50	api	59425dd2fbf7588432563aeeeac36d1d94439ac944be2676a7c4e6771886d1ae	["*"]	\N	\N	2024-06-13 08:30:51	2024-06-13 08:30:51
273	App\\Models\\User	57	api	2da9d09032b9f9aa36a20867cc6ccad478fe94527df156e66cd6d56b0e6bb493	["*"]	\N	\N	2024-06-13 14:42:46	2024-06-13 14:42:46
287	App\\Models\\User	66	api	0c79bc876207a5d34f4c1bbafb15064ea8822d03cf7b9fe6cf3a4169d8a22ee0	["*"]	2024-06-13 16:40:25	\N	2024-06-13 16:33:23	2024-06-13 16:40:25
210	App\\Models\\User	47	api	884f7fcd65fbd07f29b98ffc3b3796fba84f115e0da7c5562c983cbdf24e0f34	["*"]	\N	\N	2024-06-12 13:46:47	2024-06-12 13:46:47
189	App\\Models\\User	35	api	0a3dae7c9f13daff7aa0b682468f1a8f6ac857a9a3e1ee4213c36469d8f09b62	["*"]	2024-06-12 06:53:08	\N	2024-06-11 21:16:21	2024-06-12 06:53:08
207	App\\Models\\User	30	api	a7eddb22e6e2e34f62081e7d73c290e4ff657152fb77c42cd005fe43ac93f4b9	["*"]	2024-06-12 19:05:50	\N	2024-06-12 12:49:48	2024-06-12 19:05:50
199	App\\Models\\User	49	api	e037c7cb4afa1f828358ba858bc4a5e119332e791c4aca70e158e376a4d77706	["*"]	\N	\N	2024-06-12 10:39:46	2024-06-12 10:39:46
214	App\\Models\\User	47	api	e66deb882cc80d8a83f4c2530e46673149e446cf539acd62d42a4c2ae956c000	["*"]	\N	\N	2024-06-12 13:48:42	2024-06-12 13:48:42
323	App\\Models\\User	77	api	e8195327e2f966c916865991f80e8577f1d0114cd9919e5404c23b3f15bf39d9	["*"]	2024-06-14 08:49:04	\N	2024-06-14 08:48:41	2024-06-14 08:49:04
263	App\\Models\\User	33	api	93e11ad6e74e8f7795e881bc9171ffd122750456925b48b286c8470a0fe985fb	["*"]	\N	\N	2024-06-13 13:04:53	2024-06-13 13:04:53
230	App\\Models\\User	51	api	f270fce788592ebf73ad060121e7242181c9e7e7edf45ddb1678204c5932147d	["*"]	\N	\N	2024-06-12 21:45:00	2024-06-12 21:45:00
232	App\\Models\\User	52	api	807a64e3aa0ca6f36d990ec27c39176c550ce784f992d9947334a63293b64574	["*"]	\N	\N	2024-06-12 22:06:35	2024-06-12 22:06:35
222	App\\Models\\User	48	api	8473191a4bf31be9f0e64aaa0273caeba3ca3d5fc855f1f524189e343ba26d25	["*"]	\N	\N	2024-06-12 20:36:32	2024-06-12 20:36:32
315	App\\Models\\User	68	api	6079ec6186cd804b677793e64c7ee309fe0a56da9b53921c7078fe8c8951629e	["*"]	2024-06-14 02:22:23	\N	2024-06-14 02:20:40	2024-06-14 02:22:23
331	App\\Models\\User	67	api	b830b1a663bf716cfed54a8b202ee961871cc281a51e96efbd97a3b20945f5d9	["*"]	2024-06-14 10:43:14	\N	2024-06-14 10:42:56	2024-06-14 10:43:14
220	App\\Models\\User	15	api	bafc2caeb37f2edcbb688b8ae3caac248aa4b6fe7e8e65db536361547a4a92e7	["*"]	2024-06-13 13:35:26	\N	2024-06-12 19:44:26	2024-06-13 13:35:26
267	App\\Models\\User	33	api	160eca3bf47a50fc0bea3cc3479523243c0906658fd15d89e56184150691b9c4	["*"]	\N	\N	2024-06-13 13:49:51	2024-06-13 13:49:51
269	App\\Models\\User	33	api	35745db4112f395bba61946995a803db8fa7cb3941764cda14e49cc7f5bd89c8	["*"]	\N	\N	2024-06-13 14:14:14	2024-06-13 14:14:14
281	App\\Models\\User	64	api	fefe5dea7830004de41a875b3d2f627f6436dd55b2f7a8ffe6db37f83794eda8	["*"]	2024-06-13 14:58:10	\N	2024-06-13 14:56:47	2024-06-13 14:58:10
283	App\\Models\\User	64	api	9683ac85804114ac42046dc1c25916aff8400b76ada1534e3fbbbf8a433dc672	["*"]	\N	\N	2024-06-13 14:58:27	2024-06-13 14:58:27
309	App\\Models\\User	68	api	3837452efcd2dab8d251f631bcfda70d21d07aa5f30549c74bcc5bf005feb417	["*"]	\N	\N	2024-06-14 01:53:44	2024-06-14 01:53:44
295	App\\Models\\User	67	api	28606049cd2a311c600f002e88b78bddf0f2f4b974d3d4c08a15af8f82f95c4c	["*"]	2024-06-13 20:45:16	\N	2024-06-13 20:39:13	2024-06-13 20:45:16
297	App\\Models\\User	68	api	79cbe49f9cc8cc4a273ad6836ae0350eed5f74f0f9060997f04a64df2daa8291	["*"]	\N	\N	2024-06-13 20:48:59	2024-06-13 20:48:59
299	App\\Models\\User	68	api	c0471f689f01df1db97a9b3342d888046a4b2ab88c95b7cf1e14ab13dba88381	["*"]	\N	\N	2024-06-13 20:51:03	2024-06-13 20:51:03
311	App\\Models\\User	68	api	7dd43ca8fd02af5130bf13b1246cacee48bfaf753c9b40d020fedc332c877a09	["*"]	\N	\N	2024-06-14 02:11:12	2024-06-14 02:11:12
301	App\\Models\\User	64	api	eba9676844637bf90853ae8ce5953f8ce58c0dc9136c9e272f8c7c30570b4f61	["*"]	\N	\N	2024-06-13 20:52:59	2024-06-13 20:52:59
313	App\\Models\\User	68	api	0c5c939e5bc119fff3ee1fa6369f8d2c9852f1a964935c0cdec62143c9cc613e	["*"]	2024-06-14 02:15:03	\N	2024-06-14 02:14:51	2024-06-14 02:15:03
319	App\\Models\\User	68	api	effcccf48c011d0fb4beb48d6c2f0404110d839162b984328d4f01224713a494	["*"]	\N	\N	2024-06-14 02:31:56	2024-06-14 02:31:56
325	App\\Models\\User	66	api	9c0c2d5c9482b2ef8e789b10161eb8e435a97a8944b03b49a0fb41df6722a528	["*"]	\N	\N	2024-06-14 08:51:09	2024-06-14 08:51:09
334	App\\Models\\User	77	api	1f1e9e58eecf46c5f6b3791f529befb4ac2787f55b74ac845ec5094c66dd2a8b	["*"]	2024-06-14 10:47:57	\N	2024-06-14 10:47:38	2024-06-14 10:47:57
332	App\\Models\\User	67	api	2bdab408dc3d7bbe6d3ace91a1feff44dc416187678025b30abaf92745eaac26	["*"]	2024-06-14 10:47:37	\N	2024-06-14 10:44:46	2024-06-14 10:47:37
336	App\\Models\\User	80	api	0abeb6cb5e11cb67684b7ade06139cad571926afd8c271adc51b468be65fd613	["*"]	2024-06-14 10:51:19	\N	2024-06-14 10:50:45	2024-06-14 10:51:19
338	App\\Models\\User	67	api	6d65e408f2d49277cb8d62ebafbd1c99025f3f4ca38e99e722f8113c9a990186	["*"]	2024-06-14 10:52:47	\N	2024-06-14 10:52:10	2024-06-14 10:52:47
387	App\\Models\\User	84	api	3a7c7d85806935181aa38dfbe22ed7f9d118cf70d47c6111bc31209be4ca993b	["*"]	2024-06-15 15:23:30	\N	2024-06-15 15:22:46	2024-06-15 15:23:30
286	App\\Models\\User	15	api	c96bdca733dae22dead5ad39675fea4e0e72be46f0101eab1750d3a39d3aabe6	["*"]	2024-06-14 16:55:47	\N	2024-06-13 15:47:33	2024-06-14 16:55:47
340	App\\Models\\User	81	api	2cc221f7939da8715c8bc5284e481c66c1b6bb1ae5eacbd5d5bcb0ff389a2768	["*"]	2024-06-14 10:59:05	\N	2024-06-14 10:55:38	2024-06-14 10:59:05
368	App\\Models\\User	15	api	4371e3331fe5351cf8dfc1428f51fe4d9953e6652dc13c8f0ad42576a5873205	["*"]	2024-06-14 20:01:44	\N	2024-06-14 17:55:37	2024-06-14 20:01:44
341	App\\Models\\User	67	api	db39d4370e7169abe0b7894473f780489d610585650be4ee07893f4e48c92da3	["*"]	2024-06-14 11:03:53	\N	2024-06-14 11:03:00	2024-06-14 11:03:53
342	App\\Models\\User	67	api	5942ef4829dc271e872c5a2471e779de134fef9c92652b1d49912c76a55e94a7	["*"]	\N	\N	2024-06-14 11:03:53	2024-06-14 11:03:53
356	App\\Models\\User	30	api	7335b87639dbdd9753af7cf3889e40e84be2f70275c38646894b4bb884588830	["*"]	2024-06-14 15:45:23	\N	2024-06-14 14:47:56	2024-06-14 15:45:23
344	App\\Models\\User	80	api	49c8112c1ba520e17859600b16d6c078110c0ef7c74a46dfaca200fccdaeb24c	["*"]	\N	\N	2024-06-14 11:09:48	2024-06-14 11:09:48
358	App\\Models\\User	83	api	0aadd56b08b3ef2786eef1418aa06c2ed7ac6609fcbacbc027f0cd9e7c323a6b	["*"]	2024-06-14 15:45:34	\N	2024-06-14 15:44:55	2024-06-14 15:45:34
345	App\\Models\\User	67	api	2a2769216aa1948e3fbfed408dc685f8d7fe450478281268a4f8f9a39857e4fb	["*"]	\N	\N	2024-06-14 11:11:05	2024-06-14 11:11:05
361	App\\Models\\User	30	api	a56e3b4a4865de231fd2550a83e4a86d92282d6ba351a95be1a041c8df065cc2	["*"]	2024-06-14 15:48:53	\N	2024-06-14 15:48:52	2024-06-14 15:48:53
346	App\\Models\\User	66	api	61aad7d138d190c643bbe38825f634a4d187cec00e039439ab64574713348011	["*"]	2024-06-14 11:15:11	\N	2024-06-14 11:12:08	2024-06-14 11:15:11
373	App\\Models\\User	29	api	cc4ac72891657913bd10488ba6c7d1de307b7b0bcf336b71e6ad197d84266c7f	["*"]	2024-06-15 01:13:28	\N	2024-06-15 01:13:21	2024-06-15 01:13:28
349	App\\Models\\User	66	api	1412cad6c6ea96c229d705745da978f7a12d8756bc87b7d6d7b57dfd35295a77	["*"]	\N	\N	2024-06-14 11:23:52	2024-06-14 11:23:52
362	App\\Models\\User	82	api	fd081e03926ae3fe884389e001f8ed0181a6781e5290c764963e46e29abb2edd	["*"]	\N	\N	2024-06-14 15:50:52	2024-06-14 15:50:52
378	App\\Models\\User	15	api	6a03a6dc33e91198aecf863545f4b499c998f04bb5fa3b08300a821f9577d880	["*"]	2024-06-15 13:02:08	\N	2024-06-15 12:01:33	2024-06-15 13:02:08
386	App\\Models\\User	84	api	27a0109e55f03b1d60fa94e93ede038d908e9e8af20de9d2d3919a335cd29816	["*"]	2024-06-15 15:19:47	\N	2024-06-15 15:19:46	2024-06-15 15:19:47
384	App\\Models\\User	86	api	7417718e6a84e47d572fdd8fb58d049890453c6bed8b6af6e80eca835861ad77	["*"]	\N	\N	2024-06-15 15:08:30	2024-06-15 15:08:30
350	App\\Models\\User	30	api	fa1171f0b4ce54063d898e0ca3084bb2dec4bfd9f781b3079fec76ccc6773810	["*"]	2024-06-15 15:20:22	\N	2024-06-14 11:26:35	2024-06-15 15:20:22
360	App\\Models\\User	82	api	c2c183ae355213f9a31f53bb2eee75afab7abca9f9d899a71387ca9f5acbb310	["*"]	2024-06-14 17:03:28	\N	2024-06-14 15:48:22	2024-06-14 17:03:28
372	App\\Models\\User	82	api	6c533529b10816a48508278c1b97d24459561710604a1d7e4ecab1cd0e917cf7	["*"]	\N	\N	2024-06-14 21:50:28	2024-06-14 21:50:28
365	App\\Models\\User	82	api	ce9fcdbd129a3713e9b7d5ec97650b89ea8a7e4b337ca3defa7ee124820fe4f7	["*"]	2024-06-14 21:52:48	\N	2024-06-14 16:49:43	2024-06-14 21:52:48
354	App\\Models\\User	30	api	7608b5461438b7cd7841a596f377bf028d10dc0510cfc6a33bd602c4f60951fd	["*"]	2024-06-14 14:21:18	\N	2024-06-14 14:21:18	2024-06-14 14:21:18
375	App\\Models\\User	82	api	c9a6edcb6f182966a0d428325db76e44b0ad08dd53eb173bf57a57e898cd1dae	["*"]	2024-06-15 10:00:04	\N	2024-06-15 08:48:19	2024-06-15 10:00:04
394	App\\Models\\User	87	api	dac61f8e344b923a0d7635205c470ca151ca1df5c76477e44f5a7ac82732fd08	["*"]	2024-06-27 18:10:30	\N	2024-06-15 19:51:35	2024-06-27 18:10:30
367	App\\Models\\User	30	api	7d9598a74864c1aacd9e08765c344784629d754585af1f56edaa99c57f9f767f	["*"]	2024-06-14 17:55:15	\N	2024-06-14 16:56:49	2024-06-14 17:55:15
376	App\\Models\\User	82	api	0aa670c39f5c5debb6785afd9cd0c58177f72d622080cbce16a5120f73595cd3	["*"]	2024-06-15 10:02:21	\N	2024-06-15 10:02:06	2024-06-15 10:02:21
374	App\\Models\\User	82	api	592a3dd4dc25664595b082f775867acd7e5f2f90ef98c6875cb359de49b34311	["*"]	\N	\N	2024-06-15 08:48:17	2024-06-15 08:48:17
370	App\\Models\\User	84	api	8840add510e340bbdbaa013402b71310e30c14a38af58a457df1d0e1ec5a6ab4	["*"]	\N	\N	2024-06-14 21:22:26	2024-06-14 21:22:26
371	App\\Models\\User	84	api	7d2d1f768b4cb99698188000d255f91cf7892a21a3bf79f340f77dc8f3ea1741	["*"]	\N	\N	2024-06-14 21:22:41	2024-06-14 21:22:41
382	App\\Models\\User	85	api	9189cfdb648327c523b7cf0ff2956c34de789a215cccc7b1f252158d51d8fd7c	["*"]	\N	\N	2024-06-15 14:47:03	2024-06-15 14:47:03
388	App\\Models\\User	30	api	cdff38a4aaaea7e32870a891484252126dbdd4a4a604b981624767bf850285ae	["*"]	2024-06-15 19:16:18	\N	2024-06-15 15:29:56	2024-06-15 19:16:18
385	App\\Models\\User	84	api	1fe473d5258262cdc0bf2598da73606c49d966fe4d0a2ae5c33d7af8c0d4dfa0	["*"]	\N	\N	2024-06-15 15:19:11	2024-06-15 15:19:11
395	App\\Models\\User	84	api	80a414230dbcf89a7ff7d43aebc6ff643156045e10072fa515ef0bba17189ee6	["*"]	2024-06-15 20:36:44	\N	2024-06-15 20:02:17	2024-06-15 20:36:44
366	App\\Models\\User	30	api	d7a79474e72ad8e3400e7905a373c19c96e0f933640786f11485b86805622b38	["*"]	2024-06-23 09:56:03	\N	2024-06-14 16:50:06	2024-06-23 09:56:03
392	App\\Models\\User	85	api	6bb26f4bc8a4bb620a5d48398374bb03933e66fe6fdd4dc66159ee55382d8f33	["*"]	2024-06-15 16:49:47	\N	2024-06-15 16:40:51	2024-06-15 16:49:47
390	App\\Models\\User	87	api	d5f636f96e03275b6722b19067f61f27f377aaf63b2e431d813934d6d58f4baa	["*"]	2024-06-15 19:44:59	\N	2024-06-15 15:36:00	2024-06-15 19:44:59
393	App\\Models\\User	88	api	1684889befcd4c6f5bd229b4c5bf75f25ecb2114916138f34b9c48a8fdf7d1cf	["*"]	2024-06-15 17:03:11	\N	2024-06-15 17:00:22	2024-06-15 17:03:11
402	App\\Models\\User	93	api	bf339affe38654788d3689bca6b4d9d39d8ea97ae0e31e85b9ec1eb638cb2819	["*"]	2024-06-21 11:16:55	\N	2024-06-16 11:36:16	2024-06-21 11:16:55
398	App\\Models\\User	89	api	873f7b2396acfc45a8c075f5827dd286d605c0a442520fa5ffa8f325c8fc81af	["*"]	2024-06-15 21:43:47	\N	2024-06-15 21:33:40	2024-06-15 21:43:47
397	App\\Models\\User	89	api	d33a66efde33af6a5d2ec89e693bc528d3e1bf212b5eb217176eb1b285d38f84	["*"]	2024-06-15 21:45:26	\N	2024-06-15 21:06:53	2024-06-15 21:45:26
399	App\\Models\\User	1	api	2b8c46c128f9c479b358558168c4dc400cf655042b7b92f194630dfbbfa36b93	["*"]	\N	\N	2024-06-16 10:32:05	2024-06-16 10:32:05
400	App\\Models\\User	1	api	a605d9c41306a32d1361c361d4baaf13a969d4b87b88064c9612aaa9169c98a4	["*"]	\N	\N	2024-06-16 10:32:11	2024-06-16 10:32:11
401	App\\Models\\User	92	api	7c0c1c17c048eec0a3708d86b1120af153c0b89c014c5d4e69d24bc846fbb4ac	["*"]	2024-06-16 11:37:49	\N	2024-06-16 10:59:35	2024-06-16 11:37:49
404	App\\Models\\User	93	api	bc53b80f895e52f7e58ddc90951b85bd2fa9145dcd2511272c726d231cf02e60	["*"]	\N	\N	2024-06-16 11:39:43	2024-06-16 11:39:43
406	App\\Models\\User	92	api	e1ea140c95a27d0cd03d9979bba6dd1b0bbc9e9debf6b619bf3bb158967e845b	["*"]	2024-06-20 10:47:23	\N	2024-06-16 11:56:36	2024-06-20 10:47:23
426	App\\Models\\User	99	api	d27fdaa120b01965257caeb6936ac555c1eea42b2de5da07dca5dffabffbb209	["*"]	2024-06-25 07:17:28	\N	2024-06-21 17:21:23	2024-06-25 07:17:28
420	App\\Models\\User	29	api	af57bbee204b3631bcc41002de9f9b2ff6f12290949981f98a4874b045b5f421	["*"]	\N	\N	2024-06-21 13:43:27	2024-06-21 13:43:27
405	App\\Models\\User	94	api	141665a8a10965435d90fc953f6a09b0ccf07f9d0248902a311387ea51ac2493	["*"]	2024-07-08 12:46:44	\N	2024-06-16 11:52:00	2024-07-08 12:46:44
421	App\\Models\\User	98	api	cd6bbb75e51ca5e8d0079a5dc8bfb7fd07c5b1d7dad7f3ba437f915456d519c7	["*"]	2024-06-21 14:01:02	\N	2024-06-21 13:56:51	2024-06-21 14:01:02
411	App\\Models\\User	89	api	a97e451d7affaec328c69e00d8e6d3a6b14787dbcbde17eb4680e2713dff32e3	["*"]	2024-06-20 14:32:32	\N	2024-06-19 14:53:04	2024-06-20 14:32:32
407	App\\Models\\User	95	api	7342a06d8738848a9ebc35b1c430bfa69b37a5d7b296a3bb5d2037f69202f3ab	["*"]	2024-06-17 10:43:05	\N	2024-06-17 10:42:17	2024-06-17 10:43:05
412	App\\Models\\User	96	api	585a4e82dc82e3079810b4da9130f08e3da0f82a0835e5894344c430c19e4fbf	["*"]	2024-06-20 14:35:18	\N	2024-06-20 14:13:16	2024-06-20 14:35:18
414	App\\Models\\User	89	api	ca891331b9ad0d28056f1a3985305f43f963ef47639b610907660efab521d2b6	["*"]	2024-06-21 14:28:13	\N	2024-06-20 14:51:29	2024-06-21 14:28:13
410	App\\Models\\User	92	api	a02eba37b3a1e02531048a91219e864dff57cc4a5efefc7b01660094294842bd	["*"]	\N	\N	2024-06-17 12:28:30	2024-06-17 12:28:30
427	App\\Models\\User	100	api	593750b9031639687d676a32672925dfd1c7f8baefaf35fbee010949d32b6d66	["*"]	2024-08-16 22:59:04	\N	2024-06-21 19:20:09	2024-08-16 22:59:04
408	App\\Models\\User	92	api	065d104cbba78dfa83531daa8a454f8515051c623465dfe1e5c46993e3f5e76d	["*"]	2024-06-21 17:52:38	\N	2024-06-17 10:44:58	2024-06-21 17:52:38
409	App\\Models\\User	92	api	0a1729b0cfac674e14fa15cfad02a0d425575fc836e0d0afcec8dbb2d21e15b3	["*"]	\N	\N	2024-06-17 10:49:24	2024-06-17 10:49:24
428	App\\Models\\User	100	api	b9542a19e27dae3c554ca29997f2a1e4a4687d28bf570ed6647728e6ca4cbde2	["*"]	\N	\N	2024-06-24 16:14:35	2024-06-24 16:14:35
415	App\\Models\\User	97	api	ab20226b20b2e6cdb37c4a99ca06198c25ebe8f7bb27b2c46c7fd0f7e12b5cce	["*"]	2024-06-20 19:03:04	\N	2024-06-20 18:49:59	2024-06-20 19:03:04
424	App\\Models\\User	97	api	9a09cd0d24f309ca9f34e163a9bb0353f7c01f21527cc715d2dc5f528441ef3c	["*"]	2024-06-21 16:17:14	\N	2024-06-21 16:16:50	2024-06-21 16:17:14
430	App\\Models\\User	100	api	a7fcb29db66a84ccbfdde7b5f2c5ca2a8e54ce4017877495efe8ec62c044f10d	["*"]	\N	\N	2024-07-06 00:54:34	2024-07-06 00:54:34
429	App\\Models\\User	100	api	10e6c3f4e3dfa1ed315ba2aa050675aa27f1986f7abd2f6712a4b00de48a937a	["*"]	\N	\N	2024-06-24 18:20:41	2024-06-24 18:20:41
416	App\\Models\\User	30	api	953263e882f3066649c18f1613acde2f1a455c9774e4322f2c262bc37ec9ad2b	["*"]	2024-06-21 17:02:04	\N	2024-06-20 23:51:31	2024-06-21 17:02:04
419	App\\Models\\User	29	api	02567eabea89323812f3c0e8d2afae8aa6d2cbb53ab118a4f515e756b88811de	["*"]	2024-06-21 13:42:43	\N	2024-06-21 13:30:18	2024-06-21 13:42:43
425	App\\Models\\User	100	api	420c81760cbdbd9b6fac197254199acb30b94220f219813f66acb8f8c8848691	["*"]	\N	\N	2024-06-21 17:20:54	2024-06-21 17:20:54
431	App\\Models\\User	100	api	d572638b0c2ce817b935e3737b67c3402f5921f09da117842f0889f308acc423	["*"]	\N	\N	2024-07-06 07:16:23	2024-07-06 07:16:23
432	App\\Models\\User	100	api	afb63d1d99002ff36c1d9186e146c4f21ffe6e01b05ec893983353d88a1d15d7	["*"]	\N	\N	2024-07-06 07:16:35	2024-07-06 07:16:35
433	App\\Models\\User	100	api	9c5d31506a1829cc81edddd9d6fe41cf097d923f34da7c0e0c7802c480daf2e8	["*"]	\N	\N	2024-07-06 07:16:58	2024-07-06 07:16:58
434	App\\Models\\User	100	api	8bf441a0784e541b7e00d98f37d8ab6172ccd865125b857024c952153adb1204	["*"]	\N	\N	2024-07-06 07:18:40	2024-07-06 07:18:40
435	App\\Models\\User	100	api	45b0969ea3291d77055ca2879ea5a02ac747731e99f136793f95ff30482bfee3	["*"]	\N	\N	2024-07-06 07:20:03	2024-07-06 07:20:03
413	App\\Models\\User	89	api	4bc57ff8ba02425eeadb4ddc7d928b482aedd930c3fcd2d77087ff40b1ad5172	["*"]	2024-06-21 15:57:54	\N	2024-06-20 14:39:15	2024-06-21 15:57:54
436	App\\Models\\User	100	api	0c76fcd2c2a42117a79bd5486ea1ab260f01a3ee3ff5e1140edb8aeb87be24a5	["*"]	\N	\N	2024-07-06 07:20:09	2024-07-06 07:20:09
437	App\\Models\\User	100	api	cc8226b4681fa5defe7c33254b285b48f6756766e856374bfebf0286ecf959ff	["*"]	\N	\N	2024-07-06 07:21:43	2024-07-06 07:21:43
438	App\\Models\\User	100	api	155b64d678fec2b54adc1638e5918cc89db156bb7621a4df2dba7ea95b39f473	["*"]	\N	\N	2024-07-06 07:21:58	2024-07-06 07:21:58
439	App\\Models\\User	100	api	b3a2d510a5f557c5bde9335777cbdb25e4a4ee0cf8d13b1a61486e2c0db2bec5	["*"]	\N	\N	2024-07-06 07:27:26	2024-07-06 07:27:26
440	App\\Models\\User	100	api	de417c5eaaefca12966df291b8a944665e18cef91df5ad38f46b5f097fee72ed	["*"]	\N	\N	2024-07-06 07:27:40	2024-07-06 07:27:40
441	App\\Models\\User	102	api	ee70cd63f61ba305f1ab71fd4e99b32a43972e3e814729825ec81036b374f5bb	["*"]	\N	\N	2024-07-06 08:37:59	2024-07-06 08:37:59
442	App\\Models\\User	102	api	304225a89bc6fcf7f1d0b8ae6afd7ec229085d0f77cc833ef4377bda04f2c84b	["*"]	\N	\N	2024-07-06 08:38:49	2024-07-06 08:38:49
447	App\\Models\\User	86	api	98992735bd325ed25d87f75faf104f4e3b34cc84237f167ff4b7a7a3508c7703	["*"]	2024-08-16 16:47:15	\N	2024-07-18 13:49:27	2024-08-16 16:47:15
443	App\\Models\\User	102	api	e135e242c722075a2d917f1c3ebbfb3f2f8a4549f1ff4f1937fa1fa01963c941	["*"]	2024-07-06 08:45:52	\N	2024-07-06 08:40:27	2024-07-06 08:45:52
444	App\\Models\\User	30	api	684ede42dc4cfdc98d0ca360dfcf62cb3b7b07a6b933379ccd5e14a4244306d8	["*"]	2024-07-15 09:36:51	\N	2024-07-15 09:36:31	2024-07-15 09:36:51
446	App\\Models\\User	100	api	5c46f0ac3401775aca92bcd9cdc835955dc4267799b4624ab622ae6a9576265e	["*"]	\N	\N	2024-07-18 12:47:47	2024-07-18 12:47:47
448	App\\Models\\User	96	api	7406097d9966033b570ca8fc33a3c94c633d606f95e860969355124c48450bd9	["*"]	\N	\N	2024-08-09 06:37:03	2024-08-09 06:37:03
449	App\\Models\\User	94	api	cad927bc440bd2697d917e4c3591946794b2c7a098d644d9852b9ead4227d8fa	["*"]	\N	\N	2024-08-09 06:37:27	2024-08-09 06:37:27
450	App\\Models\\User	9	api	b69d5faecc887aae13f3921bb8a699a618a43f8c74591b6ebcc4bc45cff23623	["*"]	\N	\N	2024-08-09 06:38:19	2024-08-09 06:38:19
451	App\\Models\\User	13	api	a0b4931c7ea3c6cef141227a6af87184c763cb65ce273c9535352eecf5a17776	["*"]	\N	\N	2024-08-09 06:38:32	2024-08-09 06:38:32
452	App\\Models\\User	14	api	bd8ed6ee3286e6241ed602e479c8ff292cdd3341a7b674da3e6fcb4d3b2a4cce	["*"]	\N	\N	2024-08-09 06:38:50	2024-08-09 06:38:50
453	App\\Models\\User	16	api	20cad901d642fb649b9f446fbc44b25d9df91ad53c4f177aa9158828677f2528	["*"]	\N	\N	2024-08-09 06:38:53	2024-08-09 06:38:53
454	App\\Models\\User	28	api	216ca1d2c97da433a3aaa2b97a76e2df4c70ba3f90bb95049ec97ebc9c881990	["*"]	\N	\N	2024-08-09 06:38:56	2024-08-09 06:38:56
455	App\\Models\\User	30	api	60d1343dc877c905a6567ad202e2de8579bc3bf7ced01d2fa4281133352ef38e	["*"]	\N	\N	2024-08-09 06:39:22	2024-08-09 06:39:22
456	App\\Models\\User	34	api	627127e7045dd7651109099170ff0a5061caed1d7d7b7b613eae5966441d7937	["*"]	\N	\N	2024-08-09 06:39:35	2024-08-09 06:39:35
457	App\\Models\\User	35	api	520e13ecb4324e1e950515b7d55bfb3e6c9d8810c23982b8a360923bb9ef82f7	["*"]	\N	\N	2024-08-09 06:39:37	2024-08-09 06:39:37
458	App\\Models\\User	36	api	98ecc30ee99b522c1a690983fa640d920febb398b32b29a8b23ae25cf33e5b81	["*"]	\N	\N	2024-08-09 06:39:52	2024-08-09 06:39:52
459	App\\Models\\User	37	api	aa3ce72d98e1900ab617d63e7040f0839a7fb7ec403edf6c65de20e8828b7762	["*"]	\N	\N	2024-08-09 06:39:56	2024-08-09 06:39:56
460	App\\Models\\User	38	api	d230859930669f8750d4d57bcb7f9e2e096921d9850cf9d7ed2d94d5b9c2b9c3	["*"]	\N	\N	2024-08-09 06:39:58	2024-08-09 06:39:58
461	App\\Models\\User	39	api	d319c889387961298a83581f41b1045ab976a2eeb2db53d82ab84d2b38e69775	["*"]	\N	\N	2024-08-09 06:40:26	2024-08-09 06:40:26
462	App\\Models\\User	40	api	b2bf747bef7678a7e314653888d554cb2c8480410bed74692fd1ca6c065a583f	["*"]	\N	\N	2024-08-09 06:40:30	2024-08-09 06:40:30
463	App\\Models\\User	41	api	fc2a56d30b9e7e89b58137508db753aa43c463b9f11777bacd06c8a352092f93	["*"]	\N	\N	2024-08-09 06:40:50	2024-08-09 06:40:50
464	App\\Models\\User	42	api	930e5757a050f5ae9812f1a9c432c3777a6600ce622655d924c352b416394fd7	["*"]	\N	\N	2024-08-09 06:40:53	2024-08-09 06:40:53
465	App\\Models\\User	43	api	8523eace3753e0e7c75431e60df0a8df5541f07a3b6fe5464ca06d230e650fa2	["*"]	\N	\N	2024-08-09 06:40:55	2024-08-09 06:40:55
466	App\\Models\\User	44	api	af8bf7947018bb6a785e0a46220d88493fb608481c300b5b7c1347b9caace320	["*"]	\N	\N	2024-08-09 06:40:58	2024-08-09 06:40:58
467	App\\Models\\User	45	api	821f44a163d44dd5985f83d314ea25194444c052b3969a644aee618bf2da853c	["*"]	\N	\N	2024-08-09 06:41:08	2024-08-09 06:41:08
468	App\\Models\\User	46	api	f3c7e56944cc886656f5fb16961cacd124664f389de8102848c3504ddc442af3	["*"]	\N	\N	2024-08-09 06:41:16	2024-08-09 06:41:16
469	App\\Models\\User	47	api	3028f01204521eecb5f69159ae51476134970abb16f2300945bd1208a97a7c09	["*"]	\N	\N	2024-08-09 06:41:19	2024-08-09 06:41:19
470	App\\Models\\User	48	api	c972b4e62b45e030166e03142e9d4ad5bfae95dc20c0383e834fc75f49dac4e4	["*"]	\N	\N	2024-08-09 06:41:28	2024-08-09 06:41:28
471	App\\Models\\User	49	api	d4417ac381a6132a451d14577525e395c46017dd8c3956629677ae0a23371239	["*"]	\N	\N	2024-08-09 06:42:00	2024-08-09 06:42:00
472	App\\Models\\User	50	api	1cd15d61223f277dee62bd06791808006aa16ba4fd6e92f4319e1f9a7ed83190	["*"]	\N	\N	2024-08-09 06:42:03	2024-08-09 06:42:03
473	App\\Models\\User	51	api	6fc0c4cee00d6ecc872e8b7cf2d8c101199833638bb7893cf6c2831c4bc74236	["*"]	\N	\N	2024-08-09 06:42:05	2024-08-09 06:42:05
474	App\\Models\\User	52	api	d2b1a39c724ce7904ad1038f389a04b7cf83664f7224db8f1ac816f243e18ad6	["*"]	\N	\N	2024-08-09 06:42:07	2024-08-09 06:42:07
475	App\\Models\\User	53	api	d4a540dc26320267283c28924fe1732814ac7632402f7db23ff54c540e7c97aa	["*"]	\N	\N	2024-08-09 06:42:17	2024-08-09 06:42:17
476	App\\Models\\User	54	api	43f181c529ad4637b5da79c367da98ec4771856d737c33b4d49896fd89033640	["*"]	\N	\N	2024-08-09 06:42:19	2024-08-09 06:42:19
477	App\\Models\\User	55	api	e84b8089298fc04bc0892ae156260a82dd7aee562941ab94b7e7cc46786c0584	["*"]	\N	\N	2024-08-09 06:42:29	2024-08-09 06:42:29
478	App\\Models\\User	56	api	b69c15b2bd9ff588da0747b16790e625dee3b56175fdf2d1cc2863593fc69ee8	["*"]	\N	\N	2024-08-09 06:42:31	2024-08-09 06:42:31
479	App\\Models\\User	57	api	5a27be5d55354386d76ed537301c1889b90e93de5285fd44bb6153112e779f17	["*"]	\N	\N	2024-08-09 06:42:38	2024-08-09 06:42:38
480	App\\Models\\User	63	api	0170415e00f6cfae58d8686b01c7a0e1903150ee69fb58d9dbc2e3c5cb06a9fd	["*"]	\N	\N	2024-08-09 06:42:40	2024-08-09 06:42:40
481	App\\Models\\User	65	api	43e058cdb15fc16523387d742e63fd4d3d275775ac469b624378b0bbd06cc004	["*"]	\N	\N	2024-08-09 06:43:04	2024-08-09 06:43:04
482	App\\Models\\User	66	api	3fb5d20dc1587025b18a56ac74d4991f7072e6f4d4991c70b5253c91c73e3920	["*"]	\N	\N	2024-08-09 06:43:06	2024-08-09 06:43:06
483	App\\Models\\User	67	api	4b49a64e5f0437e37f63540f96faafd9f826b5f5ee0ba8f065154949cb1eb174	["*"]	\N	\N	2024-08-09 06:43:09	2024-08-09 06:43:09
484	App\\Models\\User	78	api	3698d05d09bc6939f6a63d3675f5371c627421f2d729a1dcc972d0eaa20e6b62	["*"]	\N	\N	2024-08-09 06:43:10	2024-08-09 06:43:10
485	App\\Models\\User	79	api	32f9524cd275963974e402b64b8bb75e917a0d07e3df6b0988248e4e7097c864	["*"]	\N	\N	2024-08-09 06:43:42	2024-08-09 06:43:42
486	App\\Models\\User	80	api	a3b493ebb7582d835565dadeba9ff1bdb2393edd7242dc5ace4d088685013c50	["*"]	\N	\N	2024-08-09 06:43:50	2024-08-09 06:43:50
487	App\\Models\\User	83	api	2a7d083c163d679348e42b4bcd5f8d12ba4303e9d774b15b3e2b02369233fcca	["*"]	\N	\N	2024-08-09 06:43:58	2024-08-09 06:43:58
488	App\\Models\\User	86	api	f463c7377bae06aee6343e3792cfc4c96424edbb8ef87a163e750ac293bbe0aa	["*"]	\N	\N	2024-08-09 06:43:59	2024-08-09 06:43:59
489	App\\Models\\User	87	api	2fa4ea7c5a9ddc7aadcc707275f94ff2c63b1f3daef3461c134445608d62c576	["*"]	\N	\N	2024-08-09 06:44:01	2024-08-09 06:44:01
490	App\\Models\\User	88	api	9beb1373a37333f772af292e65045751a6352daf6ccf00c6d06d3f3bad2c693d	["*"]	\N	\N	2024-08-09 06:44:09	2024-08-09 06:44:09
491	App\\Models\\User	89	api	23988100dd3d755c49f6a67e80c1d1753b6a74290a8ca264c9e512ea528b202a	["*"]	\N	\N	2024-08-09 06:44:18	2024-08-09 06:44:18
492	App\\Models\\User	92	api	1feff49d746c1145923c65cff8599cbeeb0100915965a5ba9c7113fadef0befd	["*"]	\N	\N	2024-08-09 06:44:51	2024-08-09 06:44:51
493	App\\Models\\User	93	api	a917cdcf7daf239fceb0e6fde461a85c5aeb0e596d2b49b1483157a8ad4fb711	["*"]	\N	\N	2024-08-09 06:44:54	2024-08-09 06:44:54
494	App\\Models\\User	94	api	5a0d6bbb1607cf933290c8d982282420003e711e5e543dffce98ce4d0a1bb1d2	["*"]	\N	\N	2024-08-09 06:44:56	2024-08-09 06:44:56
495	App\\Models\\User	97	api	903836bc066214e0fe395a46163e87b3a7da8cb753e4180d7807d0f3ff70e529	["*"]	\N	\N	2024-08-09 06:45:03	2024-08-09 06:45:03
496	App\\Models\\User	98	api	d91dceb4898e2d267366637eeac9e40b8e0ce359387b1da09692656d55ef3edd	["*"]	\N	\N	2024-08-09 06:45:11	2024-08-09 06:45:11
497	App\\Models\\User	99	api	0b1fb16407a1c4e58feb121125444c1fa3d08c30c69fa50c25a4a42806857dec	["*"]	\N	\N	2024-08-09 06:45:18	2024-08-09 06:45:18
498	App\\Models\\User	100	api	7184f6d026d0f99814fef7f68ce8270d55bfd7b327973e401e6e62c59c749750	["*"]	\N	\N	2024-08-09 06:45:21	2024-08-09 06:45:21
499	App\\Models\\User	102	api	c19f3564017eda54d138dc539fc27d302d8c84ccd1bc9295119578ed522f0d77	["*"]	\N	\N	2024-08-13 22:37:21	2024-08-13 22:37:21
500	App\\Models\\User	102	api	70b6d78727daa563d4beed906a41e1287e24e07d8edfddef612aafda022e3b80	["*"]	\N	\N	2024-08-13 22:37:45	2024-08-13 22:37:45
501	App\\Models\\User	102	api	12b5fffeeebb388371944f906bca38d7b336291e71ae27511d9d3212aee234d4	["*"]	\N	\N	2024-08-14 10:54:07	2024-08-14 10:54:07
502	App\\Models\\User	45	api	d758f3a1df48b01d760b50366fb70ee6101d304ecdabb69de50e691955937be5	["*"]	\N	\N	2024-08-14 12:24:39	2024-08-14 12:24:39
503	App\\Models\\User	109	api	d3b0bcd055e675285be132c2d0a2b1c5d2e33145317d965fee17a4c8b9fb4c64	["*"]	\N	\N	2024-08-14 12:29:51	2024-08-14 12:29:51
504	App\\Models\\User	109	api	24def3999be80daed7f3abc19207e61379f565bff84b44ce50ea03337a1acbb2	["*"]	\N	\N	2024-08-14 12:36:27	2024-08-14 12:36:27
505	App\\Models\\User	109	api	497fe4ca416e1b3c57e56a7e76dd034cbd3c0e964da8c0e79c909b9346eae4c9	["*"]	\N	\N	2024-08-14 12:36:58	2024-08-14 12:36:58
506	App\\Models\\User	110	api	3c47ff07b5ed11c1bcc6dc3e475f72bf24392d233acaf804d83ce16dbcb8a8a9	["*"]	\N	\N	2024-08-14 13:24:41	2024-08-14 13:24:41
507	App\\Models\\User	111	api	f76117673fb53ca45a8eca16065ca64ff7c62c3d455e1b03a7b11bfcc2107cb2	["*"]	\N	\N	2024-08-14 13:27:00	2024-08-14 13:27:00
508	App\\Models\\User	120	api	6591111c791ea52e78d7fc7081590a61af81ed0e45c72e7e36c8895ced26307c	["*"]	\N	\N	2024-08-14 17:16:55	2024-08-14 17:16:55
509	App\\Models\\User	9	api	3f6f2ac16a1bd2f3e5d8e1918456296389eabdad838c4f3c0515192480322044	["*"]	\N	\N	2024-08-14 17:21:06	2024-08-14 17:21:06
510	App\\Models\\User	110	api	24fedd009c331c039c103fa0eb905ffc0d7b29216904d773cf3f79a48f0e9969	["*"]	\N	\N	2024-08-14 17:26:25	2024-08-14 17:26:25
511	App\\Models\\User	110	api	685d07de79c397c4701e4a7bdeb106a3b9c2534332b58e1a0f6f73af131ef82b	["*"]	\N	\N	2024-08-14 18:18:29	2024-08-14 18:18:29
512	App\\Models\\User	125	api	3500d7c455472566dd74c5d897135caecbbcd0385c4095063c19dbc1e9bcdf7e	["*"]	\N	\N	2024-08-15 08:54:13	2024-08-15 08:54:13
513	App\\Models\\User	125	api	28fc9c0ba89da7c80df1790a64dcf8124856b919ea9f3abbd23d67e5942648f5	["*"]	\N	\N	2024-08-15 11:08:26	2024-08-15 11:08:26
514	App\\Models\\User	130	api	0ddc94e446e7ae711a23cdc38361f5ad5fce533085535c85940faf6c9c4fc639	["*"]	\N	\N	2024-08-16 09:49:04	2024-08-16 09:49:04
515	App\\Models\\User	1	api	9dc1631fcf9cbe86f635201653c5238353799068765acb31ad24b8b03d5f50bf	["*"]	\N	\N	2024-08-17 18:30:40	2024-08-17 18:30:40
516	App\\Models\\User	1	api	4fd6a96fa87d560398e5e5575e1ffec9168d51b31a7b7342bb5fccd86a62027d	["*"]	\N	\N	2024-08-17 18:30:44	2024-08-17 18:30:44
\.


--
-- Data for Name: role_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_permissions (id, user_role_id_fk, module_key, permissions, created_at, updated_at) FROM stdin;
128	2	customers	["c","r","u"]	\N	\N
129	2	customers_booking_order	["r"]	\N	\N
130	2	reporting_customers_booking_order	["c","r","u"]	\N	\N
131	2	customer_ratings	["r","u"]	\N	\N
132	2	vendors	["c","r","u","d"]	\N	\N
133	2	vendors_portfolio	["c"]	\N	\N
134	2	vendors_booking	["c","r","u","d"]	\N	\N
135	2	reporting_vendors	["r"]	\N	\N
136	2	reporting_vendors_booking	["r"]	\N	\N
137	3	dashboard	["r"]	\N	\N
138	3	admin_users	["c","r","u","d"]	\N	\N
139	3	user_roles	["c","r","u","d"]	\N	\N
140	3	customers	["r"]	\N	\N
141	3	customer_ratings	["r","u","d"]	\N	\N
142	3	reporting_customer_rating	["r"]	\N	\N
143	3	vendors	["c","r","u","d"]	\N	\N
144	3	vendors_portfolio	["c"]	\N	\N
145	3	vendors_booking	["c","r","u","d"]	\N	\N
146	3	reporting_vendors	["r"]	\N	\N
147	3	reporting_vendors_booking	["r"]	\N	\N
148	3	masters_country	["c","r","u","d"]	\N	\N
149	3	masters_category	["c","r","u","d"]	\N	\N
150	3	cms_pages	["c","r","u","d"]	\N	\N
151	3	vendor_ratings	["c","r","u","d"]	\N	\N
152	3	reporting_vendors_rating	["r"]	\N	\N
56	4	dashboard	["r"]	\N	\N
58	8	admin_users	["r","u"]	\N	\N
59	8	customers	["r","u"]	\N	\N
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, role, status, is_admin_role, created_at, updated_at, deleted_at) FROM stdin;
1	Super Admin	1	1	2024-05-03 08:46:02	2024-05-03 08:46:02	\N
2	Bookings team	1	1	2024-05-03 15:39:02	2024-05-22 16:18:34	\N
3	Admin Team	1	1	2024-05-07 14:27:02	2024-05-22 16:20:36	\N
5	fg	1	1	2024-08-14 15:31:50	2024-08-14 15:36:09	2024-08-14 15:36:09
6	qwerty	1	1	2024-08-14 17:06:18	2024-08-14 17:06:28	2024-08-14 17:06:28
8	Admin Users	1	1	2024-08-15 14:52:34	2024-08-15 14:52:34	\N
4	user2	1	1	2024-08-08 15:39:58	2024-08-19 14:44:09	2024-08-19 14:44:09
7	team	1	1	2024-08-15 08:48:16	2024-08-19 14:44:19	2024-08-19 14:44:19
\.


--
-- Data for Name: settings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.settings (id, meta_key, meta_value) FROM stdin;
4	scl_twitter	https://x.com/DXBMediaOffice?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor
7	scl_linkedin	https://www.linkedin.com/company/dubai/?trk=public_profile_experience-item_profile-section-card_image-click&originalSubdomain=pk
3	tax_percentage	5
10	company_name	Disraption
11	company_address	26985 Brighton Lane, Lake Forest, CA 92630
12	return_policies	[{"dayStart":"1","dayEnd":"4","amount":"0"},{"dayStart":"4","dayEnd":"7","amount":"50"},{"dayStart":"7","dayEnd":"15","amount":"100%"}]
13	cms_location	{"latitude":"25.1000998","longitude":"55.2380812","location_name":"Dubai Hills Mall Storm Coaster - Dubai - United Arab Emirates"}
14	cms_cancellation_policy	<p>testing cancelation policy</p>
1	whatsapp_dialcode	971
2	whatsapp_phone	559160301
5	scl_facebook	https://www.facebook.com/dubaihillstattoo/
6	scl_instagram	https://www.instagram.com/dubaihillstattoo
8	email	management@dubaihillstattoo.com
9	website	https://www.dubaihillstattoo.com
\.


--
-- Data for Name: temp_transactions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.temp_transactions (id, type, p_id, p_status, transaction_data, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: temp_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.temp_users (id, name, email, dial_code, phone, user_type_id, user_phone_otp, access_token, user_data, created_at, updated_at) FROM stdin;
3	abdul wahab	newabdulwahab22@gmail.com	92	5412365	2	1234	5a29851c59d792c08aafb788da6dec4a	{"first_name":"Abdul","last_name":"Wahab","email":"newabdulwahab22@gmail.com","dial_code":"92","phone":"5412365","gender":"male","date_of_birth":"1993-04-06","password":"testing22","device_type":"android","device_cart_id":"cart_a","fcm_token":"adgkduhuefabsfbagfafasf"}	2024-05-23 12:01:15	2024-05-23 12:01:15
4	raj kumar	raj@gmail.com	+971	9578861344	2	1234	bf849beeb2c65707e15415062c8d0e56	{"date_of_birth":"2023-05-23","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"raj@gmail.com","fcm_token":null,"first_name":"Raj","gender":"male","language":"en","last_name":"Kumar","password":"12345678","phone":"9578861344","timezone":"Asia\\/Kolkata"}	2024-05-23 15:07:24	2024-05-23 15:07:24
5	vijay karthi	vijay@gmail.co	+971	6575676576	2	1234	630ac46bb24f8ea31dad1be600bcd645	{"date_of_birth":"2022-05-23","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"vijay@gmail.co","fcm_token":null,"first_name":"Vijay","gender":"male","language":"en","last_name":"Karthi","password":"12345678","phone":"6575676576","timezone":"Asia\\/Kolkata"}	2024-05-23 15:28:11	2024-05-23 15:28:11
6	manoj k	manoj@gmail.com	+971	1234567890	2	1234	655c239ad6ef3dbf6acb0a90cc50fc37	{"date_of_birth":"2023-05-23","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"manoj@gmail.com","fcm_token":null,"first_name":"Manoj","gender":"male","language":"en","last_name":"K","password":"12345678","phone":"1234567890","timezone":"Asia\\/Kolkata"}	2024-05-23 15:30:12	2024-05-23 15:30:12
8	jaya m	jaya@gmail.com	+971	1234567899	2	1234	4e36cc059f0375997b9f2218b9a25772	{"date_of_birth":"2023-05-23","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"jaya@gmail.com","fcm_token":null,"first_name":"Jaya","gender":"male","language":"en","last_name":"M","password":"12345678","phone":"1234567899","timezone":"Asia\\/Kolkata"}	2024-05-23 15:34:22	2024-05-23 15:34:22
9	viki v	viki@gmail.com	+971	1234567898	2	1234	fbc319f2eee36a28a4b76a741108654f	{"date_of_birth":"2022-04-23","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"viki@gmail.com","fcm_token":null,"first_name":"Viki","gender":"female","language":"en","last_name":"V","password":"123456789","phone":"1234567898","timezone":"Asia\\/Kolkata"}	2024-05-23 15:49:28	2024-05-23 15:49:28
10	vinai k	vinai@gmail.com	+971	1235567890	2	1234	04d4de9d7c4d0249119bc85db53bf281	{"date_of_birth":"2024-04-23","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"vinai@gmail.com","fcm_token":null,"first_name":"Vinai","gender":"male","language":"en","last_name":"K","password":"123456789","phone":"1235567890","timezone":"Asia\\/Kolkata"}	2024-05-23 15:55:41	2024-05-23 15:55:41
11	new t	new@gmail.com	+971	9578861355	2	1234	d76fe98746a900e5cca377ed27b8690a	{"date_of_birth":"2023-05-23","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"new@gmail.com","fcm_token":"abdhjef ekef kjwf wedfw","first_name":"New","gender":"male","language":"en","last_name":"T","password":"12345678","phone":"9578861355","timezone":"Asia\\/Kolkata"}	2024-05-23 16:09:57	2024-05-23 16:09:57
83	chef1234 adersio	ghansbv@me.com	+971	526162947	2	1234	9762d0421468c24fe8d3fdc87477c60c	{"date_of_birth":"1984-06-15","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"ghansbv@me.com","fcm_token":"dY4uh9b_xUZVniGUuXjyFJ:APA91bFJaqUaOuXinOybzWsUyw0G-h2_M0mIzDLuKk2zSF-IfCLmgQhixsTvwrc055QUxzsEiRRlTd0c-RpeEF1mAB7I-oSCwS8lCQry-FcFiessaIWLI0_pdcroKqEmwRjLLLT0wT7u","first_name":"Chef1234","gender":"female","language":"en","last_name":"Adersio","password":"disraption","phone":"526162947","timezone":"Asia\\/Dubai"}	2024-06-16 10:34:43	2024-06-16 10:34:43
14	roby r	robyiosdev@gmail.com	+91	8870978253	2	1234	b28d98a99c27427d6cbf97be107247bd	{"date_of_birth":"1993-04-09","device_cart_id":"cart_a","device_type":"ios","dial_code":"+91","email":"robyiosdev@gmail.com","fcm_token":"abdhjef ekef kjwf wedfw","first_name":"Roby","gender":"male","language":"en","last_name":"R","password":"12345678","phone":"8870978253","timezone":"Asia\\/Kolkata"}	2024-05-23 17:06:25	2024-05-23 17:06:25
15	abdul wahab	abwahab232@gmail.com	92	5412364	2	1234	4348067f685e1735417f9ce6a742079f	{"first_name":"Abdul","last_name":"Wahab","email":"abwahab232@gmail.com","dial_code":"92","phone":"5412364","gender":"male","date_of_birth":"1993-04-06","password":"testing22","device_type":"android","device_cart_id":"cart_a","fcm_token":"adgkduhuefabsfbagfafasf"}	2024-05-24 05:59:30	2024-05-24 05:59:30
16	vino d	vino@gmail.co	+971	9578861244	2	1234	66ceb294985de228c51c42e18e5d6576	{"date_of_birth":"2023-05-24","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"vino@gmail.co","fcm_token":"abdhjef ekef kjwf wedfw","first_name":"Vino","gender":"male","language":"en","last_name":"D","password":"12345678","phone":"9578861244","timezone":"Asia\\/Kolkata"}	2024-05-24 11:39:39	2024-05-24 11:39:39
17	ashwin a	ashwin@gmail.com	+971	9578861342	2	1234	a65a3d66c833152a54b205cf83c4c1b9	{"date_of_birth":"2022-05-24","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"ashwin@gmail.com","fcm_token":"abdhjef ekef kjwf wedfw","first_name":"Ashwin","gender":"male","language":"en","last_name":"A","password":"12345678","phone":"9578861342","timezone":"Asia\\/Kolkata"}	2024-05-24 11:42:02	2024-05-24 11:42:02
18	maya d	maya@gmail.com	+971	9578861300	2	1234	c2ff62b27beadb0a253e8b3efa5000e5	{"date_of_birth":"2023-05-25","device_cart_id":"cart_a","device_type":"ios","dial_code":"+971","email":"maya@gmail.com","fcm_token":"abdhjef ekef kjwf wedfw","first_name":"Maya","gender":"male","language":"en","last_name":"D","password":"12345678","phone":"9578861300","timezone":"Asia\\/Kolkata"}	2024-05-25 07:53:12	2024-05-25 07:53:12
27	hanza khan	hamza@test.com	+971	5454545454	2	1234	ab828772b516341f047e5e2cbe654776	{"date_of_birth":"12-09-1999","device_cart_id":null,"device_type":"iOS","dial_code":"+971","email":"hamza@test.com","fcm_token":"abdhjef ekef kjwf wedfw","first_name":"hanza","gender":"male","last_name":"khan","password":"hamza@123","phone":"5454545454"}	2024-06-03 17:34:09	2024-06-03 17:34:09
28	maaz siddiqui	maaz@test.com	92	5412778	2	1234	38dfce41fedd76a0bf86fca3dee7c768	{"first_name":"Maaz","last_name":"Siddiqui","email":"maaz@test.com","dial_code":"92","phone":"5412778","gender":"male","date_of_birth":"1993-04-06","password":"maaz@123","device_type":"ios","device_cart_id":"cart_a","fcm_token":"adgkduhuefabsfbagfafasf"}	2024-06-03 17:39:38	2024-06-03 17:39:38
29	suleman ali	ib2suleman.ali@gmail.com	+92	3027655876	2	1234	ae05a44d2a6e536905c7d628cd93cd6f	{"date_of_birth":"1997-08-25","device_cart_id":"cart_a","device_type":"ios","dial_code":"+92","email":"ib2suleman.ali@gmail.com","fcm_token":"abdhjef ekef kjwf wedfw","first_name":"Suleman","gender":"male","language":"en","last_name":"Ali","password":"Zulfiqar@12","phone":"3027655876","timezone":"Asia\\/Karachi"}	2024-06-05 15:26:55	2024-06-05 15:26:55
30	test one	test@gmail.com	92	123123123	2	1234	5f23d9395ffe2cd23521f929300c01e4	{"first_name":"Test","last_name":"one","email":"test@gmail.com","dial_code":"92","phone":"123123123","gender":"male","date_of_birth":"1993-04-06","password":"Testing@22","device_type":"android","device_cart_id":"cart_a","fcm_token":"adgkduhuefabsfbagfafasf"}	2024-06-06 09:36:13	2024-06-06 09:36:13
35	abdul wahab	testw@gmail.com	92	541236499	2	1234	bea77525ed0b26656953cc20da5e8f5d	{"first_name":"Abdul","last_name":"Wahab","email":"testW@gmail.com","dial_code":"92","phone":"541236499","gender":"male","date_of_birth":null,"password":"testing22","device_type":"android","device_cart_id":"cart_a","fcm_token":"adgkduhuefabsfbagfafasf"}	2024-06-06 10:45:43	2024-06-06 10:45:43
46	abdul wahab	abwahab21325@gmail.com	92	54123621	2	1234	51887536cdbe2ade61be0c2808dba731	{"first_name":"Abdul","last_name":"Wahab","email":"abwahab21325@gmail.com","dial_code":"92","phone":"54123621","gender":null,"date_of_birth":null,"password":"testing22","device_type":"android","device_cart_id":"cart_a","fcm_token":"adgkduhuefabsfbagfafasf"}	2024-06-11 09:03:12	2024-06-11 09:03:12
55	abdul wahab	abwahab2325@gmail.com	92	5412360	2	1234	5c48af00ab8a14e826aa051b3e037eed	{"first_name":"Abdul","last_name":"Wahab","email":"abwahab2325@gmail.com","dial_code":"92","phone":"5412360","gender":"male","date_of_birth":"1993-04-06","password":"testing22","device_type":"android","device_cart_id":"cart_a","fcm_token":"adgkduhuefabsfbagfafasf"}	2024-06-11 15:52:33	2024-06-11 15:52:33
72	nemai sixteen	u16@mailinator.com	+91	9638527412	2	1234	e50b58ec25cc6147c473e2672e6603ea	{"date_of_birth":"2013-06-13","device_cart_id":"cart_a","device_type":"ios","dial_code":"+91","email":"u16@mailinator.com","fcm_token":"d5D-y8q5J0LisFiYIRPm2I:APA91bGtUMYrAq7NtzSsPfhjAcaODEP2g_eR6AJPj-mJgVGfPfiw7agJlQ8_Nwogp7cIfapu9-9xabT4yttT14xWBFTDrLKopfYYZv7k0LoS_qtKwUTdyRV8OCRRXsu6veJ9FEWftjbx","first_name":"Nemai","gender":"male","language":"en","last_name":"Sixteen","password":"Hello@123","phone":"9638527412","timezone":"Asia\\/Kolkata"}	2024-06-14 08:44:07	2024-06-14 08:44:07
\.


--
-- Data for Name: transactions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transactions (id, customer_id, vendor_id, order_id, transaction_id, status, amount, type, payment_method, created_at, updated_at, other_customer_id, p_trans_id, p_info, p_data) FROM stdin;
1	3	2	1	D8125975	success	20000.00	booking_full	wallet	2024-05-03 08:47:10	2024-05-03 14:02:37	\N	\N	\N	\N
3	4	2	3	D6275395	success	20000.00	booking_advance	wallet	2024-05-03 14:44:06	2024-05-03 14:44:06	\N	\N	\N	\N
2	3	2	2	D2257104	success	200.00	booking_advance	wallet	2024-05-03 13:38:35	2024-05-06 03:00:43	\N	\N	\N	\N
5	3	2	5	D7486902	success	50.00	booking_advance	wallet	2024-05-06 03:03:19	2024-05-06 03:03:19	\N	\N	\N	\N
6	3	2	6	D6029421	success	1000.00	booking_advance	wallet	2024-05-06 18:32:46	2024-05-06 18:32:46	\N	\N	\N	\N
7	3	2	7	D2405350	success	100.00	booking_advance	wallet	2024-05-06 18:38:18	2024-05-06 18:38:18	\N	\N	\N	\N
4	4	7	4	D6999783	refunded	400.00	booking_advance	wallet	2024-05-06 02:24:12	2024-05-07 13:38:29	\N	\N	\N	\N
8	3	7	8	D6942852	success	600.00	booking_advance	wallet	2024-05-07 14:16:35	2024-05-07 14:16:35	\N	\N	\N	\N
9	9	10	11	D6723817	success	7434.00	booking_full	stripe	2024-06-06 17:14:23	2024-06-06 17:14:23	\N	\N	\N	\N
10	9	10	11	D3322687	success	7434.00	booking_full	wallet	2024-06-06 17:20:35	2024-06-06 17:20:35	\N	\N	\N	\N
11	9	10	11	D8677900	success	0.00	booking_full	stripe	2024-06-06 17:21:38	2024-06-06 17:21:38	\N	\N	\N	\N
12	9	10	11	D8393938	success	0.00	booking_full	stripe	2024-06-06 17:23:28	2024-06-06 17:23:28	\N	\N	\N	\N
13	9	10	11	D1906605	success	600.00	booking_advance	stripe	2024-06-06 17:25:56	2024-06-06 17:25:56	\N	\N	\N	\N
14	9	10	11	D8998916	success	600.00	booking_advance	stripe	2024-06-06 17:28:40	2024-06-06 17:28:40	\N	\N	\N	\N
15	9	10	11	D3578195	success	0.00	booking_full	stripe	2024-06-06 17:29:33	2024-06-06 17:29:33	\N	\N	\N	\N
16	9	10	11	D2475513	success	600.00	booking_advance	stripe	2024-06-06 18:46:51	2024-06-06 18:46:51	\N	\N	\N	\N
17	9	10	11	D2228102	success	0.00	booking_full	stripe	2024-06-06 18:50:09	2024-06-06 18:50:09	\N	\N	\N	\N
18	30	10	19	D1829099	success	600.00	booking_advance	stripe	2024-06-06 19:27:59	2024-06-06 19:27:59	\N	\N	\N	\N
19	30	10	19	D5006411	success	3181.50	booking_full	stripe	2024-06-06 19:30:35	2024-06-06 19:30:35	\N	\N	\N	\N
20	30	27	21	D2056605	success	500.00	booking_advance	stripe	2024-06-06 21:12:34	2024-06-06 21:12:34	\N	\N	\N	\N
21	30	27	21	D4726485	success	1050.00	booking_full	stripe	2024-06-06 21:15:06	2024-06-06 21:15:06	\N	\N	\N	\N
22	30	27	20	D6784694	success	500.00	booking_advance	stripe	2024-06-06 22:13:52	2024-06-06 22:13:52	\N	\N	\N	\N
23	9	10	11	D4163503	success	600.00	booking_advance	stripe	2024-06-06 22:58:28	2024-06-06 22:58:28	\N	\N	\N	\N
24	9	10	11	D1794072	success	0.00	booking_full	stripe	2024-06-06 23:04:51	2024-06-06 23:04:51	\N	\N	\N	\N
25	9	10	11	D8198516	success	0.00	booking_full	stripe	2024-06-06 23:07:46	2024-06-06 23:07:46	\N	\N	\N	\N
26	4	\N	\N	D9286150	success	100.00	wallet_credit	stripe	2024-06-07 07:39:50	2024-06-07 07:39:50	\N	\N	\N	{"clientSecret":"pi_3POteSBjsMxFtgBe1hZZX7nQ_secret_ppfFZ4StGNlZGhaPIuTQAHn1M"}
27	4	2	22	D8344944	success	100.00	booking_advance	stripe	2024-06-07 08:55:50	2024-06-07 08:55:50	\N	\N	\N	{"clientSecret":"pi_3POupxBjsMxFtgBe0bLrKEaP_secret_A8jgpAEDnpc2RlOTmdAvtqIUU"}
28	30	\N	\N	D3110152	success	999999.00	wallet_credit	stripe	2024-06-07 12:32:42	2024-06-07 12:32:42	\N	\N	\N	{"clientSecret":"pi_3POxddBjsMxFtgBe0qR3U5By_secret_rej2z6k3UBSm5dnlVUfjeSYQx"}
29	9	27	24	D8792267	success	300.00	booking_advance	wallet	2024-06-07 13:46:57	2024-06-07 13:46:57	\N	\N	\N	\N
30	9	27	24	D5240679	success	300.00	booking_advance	wallet	2024-06-07 13:49:14	2024-06-07 13:49:14	\N	\N	\N	\N
31	30	27	23	D9430916	success	500.00	booking_advance	wallet	2024-06-07 14:00:41	2024-06-07 14:00:41	\N	\N	\N	\N
32	30	27	23	D5119766	success	1862.50	booking_full	wallet	2024-06-07 14:01:46	2024-06-07 14:01:46	\N	\N	\N	\N
33	28	27	25	D3092136	success	500.00	booking_advance	wallet	2024-06-07 14:11:07	2024-06-07 14:11:07	\N	\N	\N	\N
34	28	27	25	D3717205	success	2125.00	booking_full	wallet	2024-06-07 14:16:06	2024-06-07 14:16:06	\N	\N	\N	\N
35	30	\N	\N	D4117244	success	20000.00	wallet_credit	stripe	2024-06-07 16:21:47	2024-06-07 16:21:47	\N	\N	\N	{"clientSecret":"pi_3PP1mgBjsMxFtgBe1yrZSc9F_secret_7P1mq1GNwvGMFkYnQ09MFW7f4"}
36	30	\N	\N	D1523221	success	2500.00	wallet_credit	stripe	2024-06-07 16:25:08	2024-06-07 16:25:08	\N	\N	\N	{"clientSecret":"pi_3PP1qgBjsMxFtgBe072FvPAh_secret_sot93chwQovB37HgmaG7ezl4c"}
37	30	\N	\N	D3968347	success	123000.00	wallet_credit	stripe	2024-06-07 16:35:24	2024-06-07 16:35:24	\N	\N	\N	{"clientSecret":"pi_3PP20ZBjsMxFtgBe0Ivc7UmS_secret_EEehdRx9RDcFHMH3LVq2u6dHn"}
38	9	\N	\N	D9102499	success	1000.00	wallet_credit	stripe	2024-06-10 11:01:25	2024-06-10 11:01:25	\N	\N	\N	{"clientSecret":"pi_3PQ2DhBjsMxFtgBe00bisDoX_secret_SRbHFot6W3fIYEszGFcFKUthm"}
39	29	2	29	D3582667	success	100.00	booking_advance	wallet	2024-06-10 11:02:28	2024-06-10 11:02:28	\N	\N	\N	\N
40	29	2	30	D8688451	success	100.00	booking_advance	wallet	2024-06-10 11:38:02	2024-06-10 11:38:02	\N	\N	\N	\N
41	29	2	31	D6653640	success	100.00	booking_advance	stripe	2024-06-10 12:22:27	2024-06-10 12:22:27	\N	\N	\N	{"clientSecret":"pi_3PQ3SLBjsMxFtgBe1DjFF2rU_secret_u86HOUnRKFGpvFO691Uf1Ax2P"}
42	29	2	32	D4738708	success	100.00	booking_advance	stripe	2024-06-10 12:47:50	2024-06-10 12:47:50	\N	\N	\N	{"clientSecret":"pi_3PQ3stBjsMxFtgBe13NOSUVj_secret_S8LCLZUGxsqS0jF4kbqD8m5C8"}
43	9	27	24	D6246788	success	-75.00	booking_full	wallet	2024-06-10 14:24:26	2024-06-10 14:24:26	\N	\N	\N	\N
44	9	27	24	D1770418	success	0.00	booking_full	wallet	2024-06-10 14:26:13	2024-06-10 14:26:13	\N	\N	\N	\N
45	9	27	24	D3477338	success	0.00	booking_full	wallet	2024-06-10 14:28:06	2024-06-10 14:28:06	\N	\N	\N	\N
46	9	27	24	D3285090	success	0.00	booking_full	wallet	2024-06-10 14:36:39	2024-06-10 14:36:39	\N	\N	\N	\N
47	9	\N	\N	D1204368	success	12000.00	wallet_credit	stripe	2024-06-11 10:43:55	2024-06-11 10:43:55	\N	\N	\N	{"clientSecret":"pi_3PQOQZBjsMxFtgBe1PIMKKwM_secret_DqDq2GlBsLZXi1JLk17h0mMeW"}
48	29	27	34	D6278544	success	500.00	booking_advance	stripe	2024-06-11 11:44:20	2024-06-11 11:44:20	\N	\N	\N	{"clientSecret":"pi_3PQPMuBjsMxFtgBe0GeqOpoF_secret_Nz1ZaFPEYjoAyFV4llFXi2DyJ"}
49	29	27	34	D4505497	success	-2600.00	booking_full	wallet	2024-06-11 12:41:27	2024-06-11 12:41:27	\N	\N	\N	\N
50	29	2	32	D5442061	success	68.00	booking_full	stripe	2024-06-11 13:03:44	2024-06-11 13:03:44	\N	\N	\N	{"clientSecret":"pi_3PQQboBjsMxFtgBe1hAu7IKe_secret_8WzjZEJ7yhCRhtIEokz2kMNAO"}
51	35	\N	\N	D1065205	success	50000.00	wallet_credit	stripe	2024-06-11 13:39:09	2024-06-11 13:39:09	\N	\N	\N	{"clientSecret":"pi_3PQRAGBjsMxFtgBe0GbeBpBd_secret_86g6v7eWghKLAJyq3kEeXAzLn"}
52	35	27	35	D7135270	success	500.00	booking_advance	wallet	2024-06-11 16:07:02	2024-06-11 16:07:02	\N	\N	\N	\N
53	9	27	38	D8789906	success	500.00	booking_advance	wallet	2024-06-11 18:52:58	2024-06-11 18:52:58	\N	\N	\N	\N
54	35	27	39	D5492878	success	500.00	booking_advance	wallet	2024-06-11 19:47:17	2024-06-11 19:47:17	\N	\N	\N	\N
55	35	27	39	D7150422	success	-15200.00	booking_full	wallet	2024-06-11 19:48:58	2024-06-11 19:48:58	\N	\N	\N	\N
56	44	\N	\N	D8795681	success	3000.00	wallet_credit	stripe	2024-06-11 20:02:45	2024-06-11 20:02:45	\N	\N	\N	{"clientSecret":"pi_3PQX9MBjsMxFtgBe1JNvwT8F_secret_NN5CWD172JVoPM6pjUEqzxWU9"}
57	44	27	40	D1431925	success	500.00	booking_advance	wallet	2024-06-11 20:24:52	2024-06-11 20:24:52	\N	\N	\N	\N
58	35	\N	\N	D2807910	success	6000.00	wallet_credit	stripe	2024-06-11 22:12:51	2024-06-11 22:12:51	\N	\N	\N	{"clientSecret":"pi_3PQZBPBjsMxFtgBe07gSZNtd_secret_2NjKXfz0oWwYcu2nYJTgQRisE"}
59	35	\N	\N	D7207081	success	500.00	wallet_credit	stripe	2024-06-11 22:13:48	2024-06-11 22:13:48	\N	\N	\N	{"clientSecret":"pi_3PQZCJBjsMxFtgBe0IPpbmOW_secret_ixnk8Zw7kFiXZDvaNfElLXZiz"}
60	30	27	20	D3731467	success	1837.50	booking_full	wallet	2024-06-11 23:25:51	2024-06-11 23:25:51	\N	\N	\N	\N
61	4	2	22	D5543980	success	150.00	booking_reschedule	wallet	2024-06-12 00:43:20	2024-06-12 00:43:20	\N	\N	\N	\N
62	45	27	43	D6201519	success	500.00	booking_advance	stripe_card	2024-06-12 07:04:57	2024-06-12 07:04:57	\N	\N	\N	\N
63	45	27	43	D6363230	success	1600.00	booking_full	stripe_card	2024-06-12 07:43:11	2024-06-12 07:43:11	\N	\N	\N	\N
64	29	2	31	D9228727	success	110.00	booking_full	wallet	2024-06-12 09:39:22	2024-06-12 09:39:22	\N	\N	\N	\N
65	45	\N	\N	D2598371	success	5000.00	wallet_credit	stripe	2024-06-12 11:11:27	2024-06-12 11:11:27	\N	\N	\N	{"clientSecret":"pi_3PQlKsBjsMxFtgBe0hCueMiX_secret_ecuLk2jaky3cf09UM7rzgG9aY"}
66	45	27	45	D4238769	success	500.00	booking_advance	wallet	2024-06-12 11:12:03	2024-06-12 11:12:03	\N	\N	\N	\N
67	45	27	45	D2793931	success	1337.50	booking_full	wallet	2024-06-12 11:28:54	2024-06-12 11:28:54	\N	\N	\N	\N
68	29	2	46	D8518586	success	100.00	booking_advance	wallet	2024-06-12 11:34:21	2024-06-12 11:34:21	\N	\N	\N	\N
69	29	2	46	D4223757	success	-16.00	booking_full	wallet	2024-06-12 11:53:06	2024-06-12 11:53:06	\N	\N	\N	\N
70	30	25	27	D1163422	success	50.00	booking_advance	wallet	2024-06-12 19:02:49	2024-06-12 19:02:49	\N	\N	\N	\N
71	30	25	27	D8820627	success	-3830.00	booking_full	wallet	2024-06-12 19:03:41	2024-06-12 19:03:41	\N	\N	\N	\N
72	15	27	47	D8910229	success	500.00	booking_advance	wallet	2024-06-12 19:45:19	2024-06-12 19:45:19	\N	\N	\N	\N
73	15	27	47	D9049396	success	550.00	booking_full	wallet	2024-06-12 19:46:31	2024-06-12 19:46:31	\N	\N	\N	\N
74	50	\N	\N	D8537605	success	5000.00	wallet_credit	stripe	2024-06-12 21:55:12	2024-06-12 21:55:12	\N	\N	\N	{"clientSecret":"pi_3PQvNrBjsMxFtgBe07WPmT9U_secret_kKIYZBpOvGPmDGzOM2BeS8gUV"}
75	50	10	50	D4229537	success	600.00	booking_advance	wallet	2024-06-12 22:06:21	2024-06-12 22:06:21	\N	\N	\N	\N
76	50	\N	\N	D3250383	success	2000.00	wallet_credit	stripe	2024-06-12 22:23:40	2024-06-12 22:23:40	\N	\N	\N	{"clientSecret":"pi_3PQvpQBjsMxFtgBe1NNGlIQY_secret_Arb6hpjWLJykgdawQyyC8Tajb"}
77	50	10	50	D4677297	success	6204.00	booking_full	wallet	2024-06-12 22:23:51	2024-06-12 22:23:51	\N	\N	\N	\N
78	50	\N	\N	D2178475	success	1000.00	wallet_credit	stripe	2024-06-12 22:36:14	2024-06-12 22:36:14	\N	\N	\N	{"clientSecret":"pi_3PQw1aBjsMxFtgBe1k8mAZCg_secret_GGe5FZY83YHWcdPFYeLz1JUv1"}
79	29	\N	\N	D1695239	success	100.00	wallet_credit	stripe	2024-06-12 23:13:35	2024-06-12 23:13:35	\N	\N	\N	{"clientSecret":"pi_3PQwbMBjsMxFtgBe0HbvmT8Q_secret_wYn8CdF9UX5a8HU61gXBVzGzk"}
80	29	\N	\N	D6740042	success	100.00	wallet_credit	stripe	2024-06-12 23:58:59	2024-06-12 23:58:59	\N	\N	\N	{"clientSecret":"pi_3PQxJSBjsMxFtgBe0ot0RQcg_secret_7e1GkxgeVr1HAWKLmxmwffTgB"}
81	29	\N	\N	D6122743	success	100.00	wallet_credit	stripe	2024-06-13 00:01:29	2024-06-13 00:01:29	\N	\N	\N	{"clientSecret":"pi_3PQxLyBjsMxFtgBe058tmfiQ_secret_YHYQylWydDUogxvV5W2cLH43V"}
82	29	\N	\N	D1201879	success	100.00	wallet_credit	stripe	2024-06-13 00:03:48	2024-06-13 00:03:48	\N	\N	\N	{"clientSecret":"pi_3PQxO6BjsMxFtgBe04yRySNB_secret_tCjGEJaxptLXy4tnZ9siHSJVE"}
83	29	\N	\N	D3272678	success	8.00	wallet_credit	stripe	2024-06-13 00:17:04	2024-06-13 00:17:04	\N	\N	\N	{"clientSecret":"pi_3PQxanBjsMxFtgBe04ZwnBpq_secret_2gf7sZvHiZXcm4yre6jWFiHFm"}
84	29	\N	\N	D7725430	success	50.00	wallet_transfer	wallet	2024-06-13 00:41:23	2024-06-13 00:41:23	33	\N	\N	\N
85	33	\N	\N	D8488365	success	50.00	wallet_receive	wallet	2024-06-13 00:41:23	2024-06-13 00:41:23	29	\N	\N	\N
86	29	\N	\N	D9215819	success	50.00	wallet_transfer	wallet	2024-06-13 00:41:54	2024-06-13 00:41:54	33	\N	\N	\N
87	33	\N	\N	D5290301	success	50.00	wallet_receive	wallet	2024-06-13 00:41:54	2024-06-13 00:41:54	29	\N	\N	\N
88	50	10	51	D8227535	success	600.00	booking_advance	wallet	2024-06-13 08:15:37	2024-06-13 08:15:37	\N	\N	\N	\N
89	50	10	51	D5270019	success	3652.50	booking_full	stripe_card	2024-06-13 08:21:31	2024-06-13 08:21:31	\N	\N	\N	\N
90	50	\N	\N	D4015612	success	250.00	wallet_credit	stripe	2024-06-13 08:35:26	2024-06-13 08:35:26	\N	\N	\N	{"clientSecret":"pi_3PR5N8BjsMxFtgBe1gLTfgx4_secret_nePM8SO2JKqdjlQh3UitnuRZm"}
91	45	10	52	D3754394	success	600.00	booking_advance	wallet	2024-06-13 08:52:15	2024-06-13 08:52:15	\N	\N	\N	\N
92	45	10	52	D4325248	success	3652.50	booking_full	stripe_card	2024-06-13 08:53:49	2024-06-13 08:53:49	\N	\N	\N	\N
93	33	2	49	D6926796	success	100.00	booking_advance	stripe_card	2024-06-13 09:05:56	2024-06-13 09:05:56	\N	\N	\N	\N
94	33	25	53	D1006100	success	50.00	booking_advance	stripe_card	2024-06-13 09:09:37	2024-06-13 09:09:37	\N	\N	\N	\N
95	29	\N	\N	D1916922	success	50.00	wallet_transfer	wallet	2024-06-13 09:44:34	2024-06-13 09:44:34	33	\N	\N	\N
96	33	\N	\N	D1088665	success	50.00	wallet_receive	wallet	2024-06-13 09:44:34	2024-06-13 09:44:34	29	\N	\N	\N
97	29	\N	\N	D3649325	success	100.00	wallet_transfer	wallet	2024-06-13 10:16:43	2024-06-13 10:16:43	33	\N	\N	\N
98	33	\N	\N	D2716974	success	100.00	wallet_receive	wallet	2024-06-13 10:16:43	2024-06-13 10:16:43	29	\N	\N	\N
99	33	25	53	D7206215	success	34.00	booking_full	stripe_card	2024-06-13 10:25:51	2024-06-13 10:25:51	\N	\N	\N	\N
100	33	2	54	D9990231	success	100.00	booking_advance	stripe_card	2024-06-13 10:36:16	2024-06-13 10:36:16	\N	\N	\N	\N
101	33	2	55	D2032449	success	100.00	booking_advance	stripe_card	2024-06-13 10:42:03	2024-06-13 10:42:03	\N	\N	\N	\N
102	33	27	56	D4751396	success	500.00	booking_advance	stripe_card	2024-06-13 10:45:54	2024-06-13 10:45:54	\N	\N	\N	\N
103	33	27	56	D1781523	success	550.00	booking_full	stripe_card	2024-06-13 10:47:53	2024-06-13 10:47:53	\N	\N	\N	\N
104	15	2	57	D8060238	success	100.00	booking_advance	wallet	2024-06-13 10:52:58	2024-06-13 10:52:58	\N	\N	\N	\N
105	29	27	58	D9815124	success	500.00	booking_advance	stripe_card	2024-06-13 10:53:03	2024-06-13 10:53:03	\N	\N	\N	\N
106	29	27	58	D2515113	success	812.50	booking_full	stripe_card	2024-06-13 10:54:59	2024-06-13 10:54:59	\N	\N	\N	\N
107	15	2	57	D6024653	success	-16.00	booking_full	wallet	2024-06-13 11:38:24	2024-06-13 11:38:24	\N	\N	\N	\N
108	33	\N	\N	D9063411	success	252.00	wallet_credit	stripe	2024-06-13 11:54:53	2024-06-13 11:54:53	\N	\N	\N	{"clientSecret":"pi_3PR8UABjsMxFtgBe1dv00pBz_secret_9rIdrnLujQ84aifEUHh6Jjjbq"}
109	33	27	59	D5201876	success	500.00	booking_advance	wallet	2024-06-13 11:55:16	2024-06-13 11:55:16	\N	\N	\N	\N
110	33	\N	\N	D5793111	success	100.00	wallet_credit	stripe	2024-06-13 11:59:15	2024-06-13 11:59:15	\N	\N	\N	{"clientSecret":"pi_3PR8YWBjsMxFtgBe0FC39fYO_secret_HGLHOAgMaaeXlSITL2fm1Nrln"}
111	33	\N	\N	D7620168	success	711.00	wallet_credit	stripe	2024-06-13 12:00:28	2024-06-13 12:00:28	\N	\N	\N	{"clientSecret":"pi_3PR8ZkBjsMxFtgBe0ehYVroC_secret_080CYEhjXUsgtZ7by9q3qWGgj"}
112	33	27	59	D8311513	success	812.50	booking_full	wallet	2024-06-13 12:00:36	2024-06-13 12:00:36	\N	\N	\N	\N
113	56	\N	\N	D4136049	success	25000.00	wallet_credit	stripe	2024-06-13 14:42:30	2024-06-13 14:42:30	\N	\N	\N	{"clientSecret":"pi_3PRB6gBjsMxFtgBe1d6jnYC9_secret_yh3m00QnJ6xJskV3mzPlccUY1"}
114	56	27	62	D6277223	success	500.00	booking_advance	wallet	2024-06-13 14:43:27	2024-06-13 14:43:27	\N	\N	\N	\N
115	15	27	63	D3120758	success	500.00	booking_advance	wallet	2024-06-13 16:38:41	2024-06-13 16:38:41	\N	\N	\N	\N
116	15	\N	\N	D2884018	success	10000.00	wallet_credit	stripe	2024-06-13 17:08:57	2024-06-13 17:08:57	\N	\N	\N	{"clientSecret":"pi_3PRDO5BjsMxFtgBe1UVR0OOA_secret_LWec5cFgBZh2NpKwS3wzQAyPH"}
117	15	\N	\N	D8804108	success	5000.00	wallet_credit	stripe	2024-06-13 21:35:14	2024-06-13 21:35:14	\N	\N	\N	{"clientSecret":"pi_3PRHXzBjsMxFtgBe1K1tGLHf_secret_H4UJFltaD0Mg680G3HlspwUOY"}
118	76	\N	\N	D8822173	success	50000.00	wallet_credit	stripe	2024-06-14 09:16:24	2024-06-14 09:16:24	\N	\N	\N	{"clientSecret":"pi_3PRSUeBjsMxFtgBe1IJRJgPs_secret_GpzAAoF2FjLOp1NI7X7zlgpC1"}
119	76	10	64	D3081150	success	600.00	booking_advance	wallet	2024-06-14 09:18:00	2024-06-14 09:18:00	\N	\N	\N	\N
120	66	10	65	D8509377	success	600.00	booking_advance	stripe_card	2024-06-14 09:21:51	2024-06-14 09:21:51	\N	\N	\N	\N
121	76	10	64	D9915273	success	4928.25	booking_full	wallet	2024-06-14 09:34:57	2024-06-14 09:34:57	\N	\N	\N	\N
122	80	\N	\N	D1769100	success	750.00	wallet_credit	stripe	2024-06-14 11:00:06	2024-06-14 11:00:06	\N	\N	\N	{"clientSecret":"pi_3PRU6nBjsMxFtgBe1OCWGiCR_secret_rNF8QJ5NUDQ47p7nIql3ZP6es"}
123	80	10	66	D9206615	success	600.00	booking_advance	stripe_card	2024-06-14 11:04:04	2024-06-14 11:04:04	\N	\N	\N	\N
124	80	\N	\N	D2395304	success	50000.00	wallet_credit	stripe	2024-06-14 11:06:24	2024-06-14 11:06:24	\N	\N	\N	{"clientSecret":"pi_3PRUCxBjsMxFtgBe0glAMFxO_secret_mwrWo74ZNh1fpg4FRKEBjKvPP"}
125	80	10	66	D5072922	success	4077.75	booking_full	wallet	2024-06-14 11:06:29	2024-06-14 11:06:29	\N	\N	\N	\N
126	80	\N	\N	D8061487	success	5000.00	wallet_transfer	wallet	2024-06-14 11:14:55	2024-06-14 11:14:55	66	\N	\N	\N
127	66	\N	\N	D9018893	success	5000.00	wallet_receive	wallet	2024-06-14 11:14:55	2024-06-14 11:14:55	80	\N	\N	\N
128	33	\N	\N	D9273761	success	610.00	wallet_credit	stripe	2024-06-14 11:38:34	2024-06-14 11:38:34	\N	\N	\N	{"clientSecret":"pi_3PRUheBjsMxFtgBe0UizBffF_secret_vc6HTwCAPM3zZjCnovWZLjjvG"}
129	33	10	67	D1013088	success	600.00	booking_advance	wallet	2024-06-14 11:44:55	2024-06-14 11:44:55	\N	\N	\N	\N
130	30	\N	\N	D9164839	success	10.00	wallet_transfer	wallet	2024-06-14 12:17:17	2024-06-14 12:17:17	54	\N	\N	\N
131	54	\N	\N	D3591793	success	10.00	wallet_receive	wallet	2024-06-14 12:17:17	2024-06-14 12:17:17	30	\N	\N	\N
132	15	27	63	D7197973	success	25.00	booking_full	wallet	2024-06-14 13:43:43	2024-06-14 13:43:43	\N	\N	\N	\N
133	15	27	63	D7356638	success	0.00	booking_full	wallet	2024-06-14 13:52:30	2024-06-14 13:52:30	\N	\N	\N	\N
134	29	27	68	D2001355	success	500.00	booking_advance	wallet	2024-06-14 15:57:21	2024-06-14 15:57:21	\N	\N	\N	\N
135	29	27	68	D9771392	success	0.00	booking_reschedule	wallet	2024-06-14 16:05:16	2024-06-14 16:05:16	\N	\N	\N	\N
136	82	\N	\N	D2116654	success	5000.00	wallet_credit	stripe	2024-06-14 16:16:08	2024-06-14 16:16:08	\N	\N	\N	{"clientSecret":"pi_3PRZ2jBjsMxFtgBe1SsQouaY_secret_lSdTBbQojZGB01cTlK1t0ZLKv"}
137	82	7	69	D4233267	success	80.00	booking_advance	wallet	2024-06-14 16:16:12	2024-06-14 16:16:12	\N	\N	\N	\N
138	82	7	70	D9950954	success	80.00	booking_advance	wallet	2024-06-14 16:28:23	2024-06-14 16:28:23	\N	\N	\N	\N
139	82	7	70	D2023013	success	77.50	booking_full	wallet	2024-06-14 16:43:40	2024-06-14 16:43:40	\N	\N	\N	\N
140	15	27	63	D8413665	success	500.00	booking_advance	wallet	2024-06-14 17:11:36	2024-06-14 17:11:36	\N	\N	\N	\N
141	15	27	63	D9870850	success	-500.00	booking_full	wallet	2024-06-14 17:12:05	2024-06-14 17:12:05	\N	\N	\N	\N
142	15	2	71	D2962455	success	100.00	booking_advance	wallet	2024-06-14 17:13:07	2024-06-14 17:13:07	\N	\N	\N	\N
143	15	2	71	D8415329	success	-16.00	booking_full	wallet	2024-06-14 17:13:55	2024-06-14 17:13:55	\N	\N	\N	\N
144	30	27	72	D1236686	success	500.00	booking_advance	wallet	2024-06-14 18:31:58	2024-06-14 18:31:58	\N	\N	\N	\N
145	30	27	72	D1815407	success	287.50	booking_full	wallet	2024-06-14 18:33:15	2024-06-14 18:33:15	\N	\N	\N	\N
146	29	27	73	D9266135	success	500.00	booking_advance	wallet	2024-06-14 20:15:09	2024-06-14 20:15:09	\N	\N	\N	\N
147	84	\N	\N	D1629599	success	5000.00	wallet_credit	stripe	2024-06-14 20:51:48	2024-06-14 20:51:48	\N	\N	\N	{"clientSecret":"pi_3PRdLcBjsMxFtgBe0SLaZils_secret_uSVsap8lwxYXK8rrdNiDqvExf"}
148	84	10	74	D7800260	success	600.00	booking_advance	wallet	2024-06-14 20:51:54	2024-06-14 20:51:54	\N	\N	\N	\N
149	84	\N	\N	D3461391	success	5000.00	wallet_credit	stripe	2024-06-14 21:13:34	2024-06-14 21:13:34	\N	\N	\N	{"clientSecret":"pi_3PRdggBjsMxFtgBe1LGwSHaG_secret_wPQSy7pjr23bzwsoyOM14u8ON"}
150	84	10	74	D5539329	success	7479.75	booking_full	wallet	2024-06-14 21:14:25	2024-06-14 21:14:25	\N	\N	\N	\N
151	29	27	73	D6955687	success	0.00	booking_reschedule	wallet	2024-06-15 00:27:55	2024-06-15 00:27:55	\N	\N	\N	\N
152	29	2	29	D3562517	success	150.00	booking_reschedule	stripe_card	2024-06-15 00:41:50	2024-06-15 00:41:50	\N	\N	\N	\N
153	84	7	76	D2124723	success	80.00	booking_advance	wallet	2024-06-15 08:51:24	2024-06-15 08:51:24	\N	\N	\N	\N
154	82	7	75	D4149642	success	80.00	booking_advance	wallet	2024-06-15 08:51:25	2024-06-15 08:51:25	\N	\N	\N	\N
155	82	7	75	D3546832	success	0.00	booking_reschedule	wallet	2024-06-15 09:12:05	2024-06-15 09:12:05	\N	\N	\N	\N
156	82	7	77	D4126983	success	80.00	booking_advance	wallet	2024-06-15 11:59:10	2024-06-15 11:59:10	\N	\N	\N	\N
157	15	27	78	D4501534	success	500.00	booking_advance	wallet	2024-06-15 12:50:58	2024-06-15 12:50:58	\N	\N	\N	\N
158	15	27	78	D2637073	success	25.00	booking_full	wallet	2024-06-15 12:53:50	2024-06-15 12:53:50	\N	\N	\N	\N
159	29	10	79	D9076616	success	600.00	booking_advance	wallet	2024-06-15 15:08:24	2024-06-15 15:08:24	\N	\N	\N	\N
160	29	10	79	D4979378	success	0.00	booking_reschedule	wallet	2024-06-15 15:18:28	2024-06-15 15:18:28	\N	\N	\N	\N
161	87	\N	\N	D3986576	success	5000.00	wallet_credit	stripe	2024-06-15 15:39:13	2024-06-15 15:39:13	\N	\N	\N	{"clientSecret":"pi_3PRuwXBjsMxFtgBe0CSzlTAp_secret_oSfqbo9tJqMK8I5VfbAPNSvIZ"}
162	87	2	80	D5866834	success	100.00	booking_advance	wallet	2024-06-15 15:39:23	2024-06-15 15:39:23	\N	\N	\N	\N
163	87	2	80	D3124900	success	-730.00	booking_full	wallet	2024-06-15 15:40:21	2024-06-15 15:40:21	\N	\N	\N	\N
164	87	\N	\N	D2132092	success	500.00	wallet_credit	stripe	2024-06-15 16:21:15	2024-06-15 16:21:15	\N	\N	\N	{"clientSecret":"pi_3PRvbCBjsMxFtgBe0zTFReOo_secret_1ctb4k8QWBVTL6nFhMiaN2Zrr"}
165	82	7	77	D7977769	success	0.00	booking_reschedule	wallet	2024-06-15 16:22:59	2024-06-15 16:22:59	\N	\N	\N	\N
166	85	\N	\N	D8083080	success	500.00	wallet_credit	stripe	2024-06-15 16:44:39	2024-06-15 16:44:39	\N	\N	\N	{"clientSecret":"pi_3PRvxyBjsMxFtgBe1yImT9hW_secret_58jPwwDqxGZNu0GeuD3gmLghq"}
167	85	7	81	D5800304	success	80.00	booking_advance	wallet	2024-06-15 16:44:46	2024-06-15 16:44:46	\N	\N	\N	\N
168	85	7	81	D5425947	success	77.50	booking_full	wallet	2024-06-15 16:48:08	2024-06-15 16:48:08	\N	\N	\N	\N
169	88	\N	\N	D9204557	success	5000.00	wallet_credit	stripe	2024-06-15 17:02:58	2024-06-15 17:02:58	\N	\N	\N	{"clientSecret":"pi_3PRwFgBjsMxFtgBe1HFg4rBg_secret_6EyfPpzd3uqRa9kNeiqNtDAnF"}
170	88	7	82	D2934730	success	80.00	booking_advance	wallet	2024-06-15 17:03:07	2024-06-15 17:03:07	\N	\N	\N	\N
171	82	27	83	D3269410	success	500.00	booking_advance	wallet	2024-06-15 17:20:55	2024-06-15 17:20:55	\N	\N	\N	\N
172	82	\N	\N	D8434953	success	500.00	wallet_credit	stripe	2024-06-15 17:48:04	2024-06-15 17:48:04	\N	\N	\N	{"clientSecret":"pi_3PRwxABjsMxFtgBe0AZeeOT9_secret_84f7PjddnlKh06OKvw3DMxdoo"}
173	82	27	83	D3274709	success	100.00	booking_reschedule	wallet	2024-06-15 17:49:32	2024-06-15 17:49:32	\N	\N	\N	\N
174	87	2	84	D2282288	success	100.00	booking_advance	stripe_card	2024-06-15 17:56:05	2024-06-15 17:56:05	\N	\N	\N	\N
175	87	2	85	D7965739	success	100.00	booking_advance	stripe_card	2024-06-15 18:01:51	2024-06-15 18:01:51	\N	\N	\N	\N
176	87	2	85	D7088123	success	100.00	booking_advance	stripe_card	2024-06-15 18:09:45	2024-06-15 18:09:45	\N	\N	\N	\N
177	87	2	85	D7501970	success	100.00	booking_advance	stripe_card	2024-06-15 18:12:40	2024-06-15 18:12:40	\N	\N	\N	\N
178	87	2	85	D6202211	success	100.00	booking_advance	stripe_card	2024-06-15 18:15:38	2024-06-15 18:15:38	\N	\N	\N	\N
179	29	10	86	D4882834	success	600.00	booking_advance	wallet	2024-06-15 18:20:01	2024-06-15 18:20:01	\N	\N	\N	\N
180	87	2	85	D8174825	success	100.00	booking_advance	stripe_card	2024-06-15 18:35:11	2024-06-15 18:35:11	\N	\N	\N	\N
181	87	2	87	D5259290	success	100.00	booking_advance	stripe_card	2024-06-15 18:53:10	2024-06-15 18:53:10	\N	\N	\N	\N
182	87	2	87	D6482341	success	-646.00	booking_full	wallet	2024-06-15 18:55:58	2024-06-15 18:55:58	\N	\N	\N	\N
183	87	27	88	D1918595	success	500.00	booking_advance	stripe_card	2024-06-15 19:23:24	2024-06-15 19:23:24	\N	\N	\N	\N
184	29	10	86	D5029654	success	0.00	booking_reschedule	wallet	2024-06-15 19:38:36	2024-06-15 19:38:36	\N	\N	\N	\N
185	29	10	86	D9521351	success	200.00	booking_reschedule	wallet	2024-06-15 19:48:25	2024-06-15 19:48:25	\N	\N	\N	\N
186	84	27	93	D7746116	success	500.00	booking_advance	wallet	2024-06-15 20:11:42	2024-06-15 20:11:42	\N	\N	\N	\N
187	87	7	94	D7952399	success	80.00	booking_advance	stripe_card	2024-06-15 20:12:36	2024-06-15 20:12:36	\N	\N	\N	\N
188	84	7	76	D7981612	success	35.50	booking_full	wallet	2024-06-15 20:21:28	2024-06-15 20:21:28	\N	\N	\N	\N
189	87	7	95	D4531153	success	80.00	booking_advance	stripe_card	2024-06-15 20:21:54	2024-06-15 20:21:54	\N	\N	\N	\N
190	87	7	96	D3017301	success	80.00	booking_advance	stripe_card	2024-06-15 20:29:19	2024-06-15 20:29:19	\N	\N	\N	\N
191	87	7	96	D2498883	success	560.50	booking_full	wallet	2024-06-15 20:32:09	2024-06-15 20:32:09	\N	\N	\N	\N
192	87	7	94	D3431384	success	-59.00	booking_full	wallet	2024-06-15 20:33:06	2024-06-15 20:33:06	\N	\N	\N	\N
193	87	7	96	D4582137	success	80.00	booking_advance	stripe_card	2024-06-15 20:46:50	2024-06-15 20:46:50	\N	\N	\N	\N
194	89	\N	\N	D2977482	success	2000.00	wallet_credit	stripe	2024-06-15 21:01:22	2024-06-15 21:01:22	\N	\N	\N	{"clientSecret":"pi_3PRzyRBjsMxFtgBe0w6gCeA8_secret_hfLYqQvX8vWq8XxBO4WOpbVpB"}
195	89	27	97	D9706492	success	500.00	booking_advance	wallet	2024-06-15 21:01:29	2024-06-15 21:01:29	\N	\N	\N	\N
196	89	27	97	D8030248	success	0.00	booking_reschedule	wallet	2024-06-15 21:08:19	2024-06-15 21:08:19	\N	\N	\N	\N
197	89	27	97	D4427576	success	150.00	booking_reschedule	wallet	2024-06-15 21:10:16	2024-06-15 21:10:16	\N	\N	\N	\N
198	89	27	97	D9014486	success	0.00	booking_reschedule	wallet	2024-06-15 21:11:27	2024-06-15 21:11:27	\N	\N	\N	\N
199	89	27	97	D5554328	success	150.00	booking_reschedule	wallet	2024-06-15 21:11:57	2024-06-15 21:11:57	\N	\N	\N	\N
200	89	27	97	D9722139	success	5143.75	booking_full	stripe_card	2024-06-15 21:13:48	2024-06-15 21:13:48	\N	\N	\N	\N
201	89	27	98	D1142573	success	500.00	booking_advance	stripe_card	2024-06-15 21:32:46	2024-06-15 21:32:46	\N	\N	\N	\N
202	89	27	98	D5267131	success	943.75	booking_full	wallet	2024-06-15 21:34:21	2024-06-15 21:34:21	\N	\N	\N	\N
203	82	27	83	D7448169	success	200.00	booking_reschedule	stripe_card	2024-06-15 22:31:09	2024-06-15 22:31:09	\N	\N	\N	\N
204	82	27	83	D8642694	success	200.00	booking_reschedule	stripe_apple	2024-06-15 22:40:16	2024-06-15 22:40:16	\N	\N	\N	\N
205	82	27	83	D4293965	success	200.00	booking_reschedule	stripe_card	2024-06-15 23:16:44	2024-06-15 23:16:44	\N	\N	\N	\N
206	82	27	83	D9733142	success	200.00	booking_reschedule	stripe_card	2024-06-15 23:18:53	2024-06-15 23:18:53	\N	\N	\N	\N
207	82	27	83	D7755528	success	150.00	booking_reschedule	wallet	2024-06-15 23:40:15	2024-06-15 23:40:15	\N	\N	\N	\N
208	82	27	83	D9170094	success	150.00	booking_reschedule	wallet	2024-06-15 23:43:08	2024-06-15 23:43:08	\N	\N	\N	\N
209	82	27	83	D5064991	success	150.00	booking_reschedule	wallet	2024-06-15 23:45:04	2024-06-15 23:45:04	\N	\N	\N	\N
210	29	10	86	D8507174	success	200.00	booking_reschedule	wallet	2024-06-16 09:44:35	2024-06-16 09:44:35	\N	\N	\N	\N
211	92	\N	\N	D4879858	success	5000.00	wallet_credit	stripe	2024-06-16 11:29:37	2024-06-16 11:29:37	\N	\N	\N	{"clientSecret":"pi_3PSDWgBjsMxFtgBe1YLyg5ur_secret_adTWoXwdu1gAZqBlaVExRYsmn"}
212	92	27	99	D2947552	success	500.00	booking_advance	wallet	2024-06-16 11:29:42	2024-06-16 11:29:42	\N	\N	\N	\N
213	92	27	99	D6248768	success	150.00	booking_reschedule	wallet	2024-06-16 11:32:02	2024-06-16 11:32:02	\N	\N	\N	\N
214	92	27	99	D8058576	success	150.00	booking_reschedule	wallet	2024-06-16 11:32:50	2024-06-16 11:32:50	\N	\N	\N	\N
215	92	27	99	D1949468	success	1993.75	booking_full	wallet	2024-06-16 11:36:09	2024-06-16 11:36:09	\N	\N	\N	\N
216	29	10	101	D4116837	success	600.00	booking_advance	wallet	2024-06-17 08:48:42	2024-06-17 08:48:42	\N	\N	\N	\N
217	29	10	101	D4143779	success	0.00	booking_reschedule	wallet	2024-06-17 09:34:10	2024-06-17 09:34:10	\N	\N	\N	\N
218	92	27	100	D1597675	success	500.00	booking_advance	wallet	2024-06-17 10:46:31	2024-06-17 10:46:31	\N	\N	\N	\N
219	29	10	101	D1840128	success	200.00	booking_reschedule	stripe_card	2024-06-17 10:55:18	2024-06-17 10:55:18	\N	\N	\N	\N
220	29	10	102	D9943841	success	600.00	booking_advance	wallet	2024-06-17 11:11:18	2024-06-17 11:11:18	\N	\N	\N	\N
221	29	10	102	D1437351	success	400.00	booking_reschedule	stripe_card	2024-06-17 11:21:01	2024-06-17 11:21:01	\N	\N	\N	\N
283	33	\N	\N	D8087303	success	100.00	wallet_receive	wallet	2024-06-21 13:32:48	2024-06-21 13:32:48	29	\N	\N	\N
222	89	\N	\N	D8132787	success	5000.00	wallet_credit	stripe	2024-06-19 15:09:52	2024-06-19 15:09:52	\N	\N	\N	{"clientSecret":"pi_3PTMO9BjsMxFtgBe1aKahfvO_secret_WCnoqVSmO5HMTLjYhu6Ipoh0F"}
223	89	27	103	D2983864	success	500.00	booking_advance	wallet	2024-06-19 15:12:28	2024-06-19 15:12:28	\N	\N	\N	\N
224	89	27	103	D8800083	success	150.00	booking_reschedule	wallet	2024-06-19 15:16:14	2024-06-19 15:16:14	\N	\N	\N	\N
225	89	27	103	D1836341	success	0.00	booking_reschedule	wallet	2024-06-19 15:21:33	2024-06-19 15:21:33	\N	\N	\N	\N
226	89	27	103	D6840561	success	2256.25	booking_full	wallet	2024-06-19 15:22:21	2024-06-19 15:22:21	\N	\N	\N	\N
227	82	27	83	D8496565	success	150.00	booking_reschedule	wallet	2024-06-20 10:33:36	2024-06-20 10:33:36	\N	\N	\N	\N
228	82	27	83	D9425300	success	0.00	booking_reschedule	wallet	2024-06-20 10:34:05	2024-06-20 10:34:05	\N	\N	\N	\N
229	82	\N	\N	D2861387	success	400.00	wallet_transfer	wallet	2024-06-20 10:35:06	2024-06-20 10:35:06	92	\N	\N	\N
230	92	\N	\N	D4904738	success	400.00	wallet_receive	wallet	2024-06-20 10:35:06	2024-06-20 10:35:06	82	\N	\N	\N
231	82	\N	\N	D9656333	success	502.50	wallet_transfer	wallet	2024-06-20 10:35:49	2024-06-20 10:35:49	92	\N	\N	\N
232	92	\N	\N	D4114719	success	502.50	wallet_receive	wallet	2024-06-20 10:35:49	2024-06-20 10:35:49	82	\N	\N	\N
233	82	25	104	D2393365	success	50.00	booking_advance	wallet	2024-06-20 12:59:10	2024-06-20 12:59:10	\N	\N	\N	\N
234	82	25	104	D5947112	success	580.00	booking_full	wallet	2024-06-20 13:05:23	2024-06-20 13:05:23	\N	\N	\N	\N
235	96	\N	\N	D8376135	success	600.00	wallet_credit	stripe	2024-06-20 14:16:19	2024-06-20 14:16:19	\N	\N	\N	{"clientSecret":"pi_3PTi2ABjsMxFtgBe1K5UwCsq_secret_P4cQb3ZD4DDLwiJnSuObieohk"}
236	96	27	105	D9352201	success	500.00	booking_advance	wallet	2024-06-20 14:16:26	2024-06-20 14:16:26	\N	\N	\N	\N
237	89	27	106	D9405229	success	500.00	booking_advance	wallet	2024-06-20 14:25:53	2024-06-20 14:25:53	\N	\N	\N	\N
238	96	\N	\N	D7053807	success	50.00	wallet_transfer	wallet	2024-06-20 14:31:23	2024-06-20 14:31:23	92	\N	\N	\N
239	92	\N	\N	D4356714	success	50.00	wallet_receive	wallet	2024-06-20 14:31:23	2024-06-20 14:31:23	96	\N	\N	\N
240	89	27	106	D1633761	success	0.00	booking_reschedule	wallet	2024-06-20 15:08:39	2024-06-20 15:08:39	\N	\N	\N	\N
241	89	27	106	D7023516	success	150.00	booking_reschedule	wallet	2024-06-20 15:40:07	2024-06-20 15:40:07	\N	\N	\N	\N
242	89	\N	\N	D6369886	success	560000.00	wallet_credit	stripe	2024-06-20 15:43:20	2024-06-20 15:43:20	\N	\N	\N	{"clientSecret":"pi_3PTjOKBjsMxFtgBe0if8LtyR_secret_4navPcysyumrtPRPJ8Yv6g5Uw"}
243	89	27	106	D1658658	success	2650.00	booking_full	wallet	2024-06-20 15:43:33	2024-06-20 15:43:33	\N	\N	\N	\N
244	89	27	108	D8202445	success	500.00	booking_advance	wallet	2024-06-20 15:48:20	2024-06-20 15:48:20	\N	\N	\N	\N
245	89	27	108	D5043841	success	150.00	booking_reschedule	wallet	2024-06-20 15:52:35	2024-06-20 15:52:35	\N	\N	\N	\N
246	89	27	108	D9360668	success	150.00	booking_reschedule	stripe_card	2024-06-20 15:53:16	2024-06-20 15:53:16	\N	\N	\N	\N
247	89	27	108	D2913793	success	1337.50	booking_full	wallet	2024-06-20 15:54:29	2024-06-20 15:54:29	\N	\N	\N	\N
248	89	27	109	D5594261	success	500.00	booking_advance	wallet	2024-06-20 17:27:44	2024-06-20 17:27:44	\N	\N	\N	\N
249	89	27	109	D8294904	success	1862.50	booking_full	wallet	2024-06-20 17:52:19	2024-06-20 17:52:19	\N	\N	\N	\N
250	89	\N	\N	D1724753	success	100.00	wallet_transfer	wallet	2024-06-20 18:25:37	2024-06-20 18:25:37	92	\N	\N	\N
251	92	\N	\N	D3059764	success	100.00	wallet_receive	wallet	2024-06-20 18:25:37	2024-06-20 18:25:37	89	\N	\N	\N
252	89	\N	\N	D7743778	success	100.00	wallet_transfer	wallet	2024-06-20 18:28:18	2024-06-20 18:28:18	92	\N	\N	\N
253	92	\N	\N	D3059577	success	100.00	wallet_receive	wallet	2024-06-20 18:28:18	2024-06-20 18:28:18	89	\N	\N	\N
254	97	27	110	D4321739	success	500.00	booking_advance	stripe_card	2024-06-20 18:54:07	2024-06-20 18:54:07	\N	\N	\N	\N
255	97	27	110	D7965389	success	0.00	booking_reschedule	wallet	2024-06-20 18:56:11	2024-06-20 18:56:11	\N	\N	\N	\N
256	97	\N	\N	D1574956	success	5000.00	wallet_credit	stripe	2024-06-20 18:57:48	2024-06-20 18:57:48	\N	\N	\N	{"clientSecret":"pi_3PTmQWBjsMxFtgBe1B3edxFr_secret_oSR1NFBbN8wTZfxtl2bCCfd7v"}
257	97	27	110	D1964250	success	150.00	booking_reschedule	wallet	2024-06-20 18:58:28	2024-06-20 18:58:28	\N	\N	\N	\N
258	97	27	110	D3580754	success	0.00	booking_reschedule	wallet	2024-06-20 18:58:52	2024-06-20 18:58:52	\N	\N	\N	\N
259	97	27	110	D4348812	success	2256.25	booking_full	wallet	2024-06-20 18:59:45	2024-06-20 18:59:45	\N	\N	\N	\N
260	97	\N	\N	D8446863	success	500.00	wallet_transfer	wallet	2024-06-20 19:01:40	2024-06-20 19:01:40	92	\N	\N	\N
261	92	\N	\N	D2186575	success	500.00	wallet_receive	wallet	2024-06-20 19:01:40	2024-06-20 19:01:40	97	\N	\N	\N
262	89	\N	\N	D5029528	success	800.00	wallet_transfer	wallet	2024-06-20 19:02:38	2024-06-20 19:02:38	97	\N	\N	\N
263	97	\N	\N	D7118650	success	800.00	wallet_receive	wallet	2024-06-20 19:02:38	2024-06-20 19:02:38	89	\N	\N	\N
264	89	\N	\N	D1767274	success	100.00	wallet_transfer	wallet	2024-06-20 23:52:06	2024-06-20 23:52:06	30	\N	\N	\N
265	30	\N	\N	D1156754	success	100.00	wallet_receive	wallet	2024-06-20 23:52:06	2024-06-20 23:52:06	89	\N	\N	\N
266	89	\N	\N	D2736025	success	100.00	wallet_transfer	wallet	2024-06-21 00:12:24	2024-06-21 00:12:24	30	\N	\N	\N
267	30	\N	\N	D1588881	success	100.00	wallet_receive	wallet	2024-06-21 00:12:24	2024-06-21 00:12:24	89	\N	\N	\N
268	89	\N	\N	D3763201	success	100.00	wallet_transfer	wallet	2024-06-21 11:53:13	2024-06-21 11:53:13	30	\N	\N	\N
269	30	\N	\N	D2408890	success	100.00	wallet_receive	wallet	2024-06-21 11:53:13	2024-06-21 11:53:13	89	\N	\N	\N
270	89	\N	\N	D4838877	success	100.00	wallet_transfer	wallet	2024-06-21 12:01:26	2024-06-21 12:01:26	30	\N	\N	\N
271	30	\N	\N	D6811694	success	100.00	wallet_receive	wallet	2024-06-21 12:01:26	2024-06-21 12:01:26	89	\N	\N	\N
272	89	\N	\N	D1904326	success	100.00	wallet_transfer	wallet	2024-06-21 12:02:22	2024-06-21 12:02:22	30	\N	\N	\N
273	30	\N	\N	D4450609	success	100.00	wallet_receive	wallet	2024-06-21 12:02:22	2024-06-21 12:02:22	89	\N	\N	\N
274	89	\N	\N	D6512332	success	100.00	wallet_transfer	wallet	2024-06-21 12:04:32	2024-06-21 12:04:32	30	\N	\N	\N
275	30	\N	\N	D4122341	success	100.00	wallet_receive	wallet	2024-06-21 12:04:32	2024-06-21 12:04:32	89	\N	\N	\N
276	89	\N	\N	D8759882	success	100.00	wallet_transfer	wallet	2024-06-21 12:35:35	2024-06-21 12:35:35	30	\N	\N	\N
277	30	\N	\N	D8019112	success	100.00	wallet_receive	wallet	2024-06-21 12:35:35	2024-06-21 12:35:35	89	\N	\N	\N
278	89	\N	\N	D4093685	success	100.00	wallet_transfer	wallet	2024-06-21 12:35:57	2024-06-21 12:35:57	30	\N	\N	\N
279	30	\N	\N	D8761564	success	100.00	wallet_receive	wallet	2024-06-21 12:35:57	2024-06-21 12:35:57	89	\N	\N	\N
280	89	\N	\N	D6565910	success	100.00	wallet_transfer	wallet	2024-06-21 12:36:14	2024-06-21 12:36:14	30	\N	\N	\N
281	30	\N	\N	D6094818	success	100.00	wallet_receive	wallet	2024-06-21 12:36:14	2024-06-21 12:36:14	89	\N	\N	\N
282	29	\N	\N	D4354196	success	100.00	wallet_transfer	wallet	2024-06-21 13:32:48	2024-06-21 13:32:48	33	\N	\N	\N
284	29	\N	\N	D1116302	success	10.00	wallet_transfer	wallet	2024-06-21 13:36:47	2024-06-21 13:36:47	33	\N	\N	\N
285	33	\N	\N	D9445554	success	10.00	wallet_receive	wallet	2024-06-21 13:36:47	2024-06-21 13:36:47	29	\N	\N	\N
286	29	\N	\N	D9617042	success	10.00	wallet_transfer	wallet	2024-06-21 13:42:43	2024-06-21 13:42:43	33	\N	\N	\N
287	33	\N	\N	D2717211	success	10.00	wallet_receive	wallet	2024-06-21 13:42:43	2024-06-21 13:42:43	29	\N	\N	\N
288	97	27	111	D2065043	success	500.00	booking_advance	wallet	2024-06-21 13:46:03	2024-06-21 13:46:03	\N	\N	\N	\N
289	97	27	111	D5785206	success	0.00	booking_reschedule	wallet	2024-06-21 13:47:16	2024-06-21 13:47:16	\N	\N	\N	\N
290	97	27	111	D3725835	success	150.00	booking_reschedule	wallet	2024-06-21 13:47:59	2024-06-21 13:47:59	\N	\N	\N	\N
291	97	27	111	D4024579	success	0.00	booking_reschedule	wallet	2024-06-21 13:48:26	2024-06-21 13:48:26	\N	\N	\N	\N
292	97	27	111	D9357205	success	150.00	booking_reschedule	wallet	2024-06-21 13:48:57	2024-06-21 13:48:57	\N	\N	\N	\N
293	89	\N	\N	D9996268	success	5000.00	wallet_transfer	wallet	2024-06-21 13:52:51	2024-06-21 13:52:51	97	\N	\N	\N
294	97	\N	\N	D4172872	success	5000.00	wallet_receive	wallet	2024-06-21 13:52:51	2024-06-21 13:52:51	89	\N	\N	\N
295	98	\N	\N	D3616517	success	510.00	wallet_credit	stripe	2024-06-21 14:00:09	2024-06-21 14:00:09	\N	\N	\N	{"clientSecret":"pi_3PU4G1BjsMxFtgBe02Lqq23K_secret_JHXhug6cJHBFerpjpzDmx3PYj"}
296	98	27	112	D3750528	success	500.00	booking_advance	wallet	2024-06-21 14:00:18	2024-06-21 14:00:18	\N	\N	\N	\N
297	89	\N	\N	D5938497	success	200.00	wallet_transfer	wallet	2024-06-21 14:50:29	2024-06-21 14:50:29	30	\N	\N	\N
298	30	\N	\N	D4805755	success	200.00	wallet_receive	wallet	2024-06-21 14:50:29	2024-06-21 14:50:29	89	\N	\N	\N
299	30	2	113	D4603031	success	100.00	booking_advance	wallet	2024-06-21 14:53:47	2024-06-21 14:53:47	\N	\N	\N	\N
300	99	\N	\N	D8711591	success	700.00	wallet_credit	stripe	2024-06-21 15:53:38	2024-06-21 15:53:38	\N	\N	\N	{"clientSecret":"pi_3PU61tBjsMxFtgBe1qpb7cyO_secret_cKBFtlAa9agW9huY3jRJTpydH"}
301	99	27	114	D8584000	success	500.00	booking_advance	wallet	2024-06-21 15:53:44	2024-06-21 15:53:44	\N	\N	\N	\N
302	99	27	114	D3081050	success	50.00	booking_reschedule	wallet	2024-06-21 15:54:34	2024-06-21 15:54:34	\N	\N	\N	\N
303	99	27	114	D7106404	success	150.00	booking_reschedule	stripe_card	2024-06-21 15:55:29	2024-06-21 15:55:29	\N	\N	\N	\N
304	99	27	114	D9278301	success	0.00	booking_reschedule	wallet	2024-06-21 15:55:43	2024-06-21 15:55:43	\N	\N	\N	\N
305	99	27	114	D8095686	success	1600.00	booking_full	stripe_card	2024-06-21 15:56:38	2024-06-21 15:56:38	\N	\N	\N	\N
306	89	\N	\N	D1670207	success	800.00	wallet_transfer	wallet	2024-06-21 15:57:51	2024-06-21 15:57:51	99	\N	\N	\N
307	99	\N	\N	D9002449	success	800.00	wallet_receive	wallet	2024-06-21 15:57:51	2024-06-21 15:57:51	89	\N	\N	\N
308	99	2	115	D1641594	success	100.00	booking_advance	wallet	2024-06-21 16:13:38	2024-06-21 16:13:38	\N	\N	\N	\N
309	99	2	115	D7826503	success	0.00	booking_reschedule	wallet	2024-06-21 16:14:17	2024-06-21 16:14:17	\N	\N	\N	\N
310	99	2	115	D5949821	success	150.00	booking_reschedule	wallet	2024-06-21 16:15:04	2024-06-21 16:15:04	\N	\N	\N	\N
311	99	2	115	D2515620	success	299.00	booking_full	wallet	2024-06-21 16:15:35	2024-06-21 16:15:35	\N	\N	\N	\N
312	97	\N	\N	D2980620	success	50.00	wallet_transfer	wallet	2024-06-21 16:17:08	2024-06-21 16:17:08	99	\N	\N	\N
313	99	\N	\N	D4821566	success	50.00	wallet_receive	wallet	2024-06-21 16:17:08	2024-06-21 16:17:08	97	\N	\N	\N
314	30	27	116	D1318561	success	500.00	booking_advance	wallet	2024-06-21 16:51:04	2024-06-21 16:51:04	\N	\N	\N	\N
315	30	27	116	D5234958	success	943.75	booking_full	wallet	2024-06-21 16:56:40	2024-06-21 16:56:40	\N	\N	\N	\N
316	30	2	113	D3407831	success	152.00	booking_full	wallet	2024-06-21 16:59:17	2024-06-21 16:59:17	\N	\N	\N	\N
317	99	\N	\N	D4880793	success	500.00	wallet_credit	stripe	2024-06-21 17:23:17	2024-06-21 17:23:17	\N	\N	\N	{"clientSecret":"pi_3PU7QfBjsMxFtgBe0LL5uQ49_secret_ez4U8dvA7yY1FoJdnoxSvck3T"}
318	99	27	117	D8975569	success	500.00	booking_advance	wallet	2024-06-21 17:23:22	2024-06-21 17:23:22	\N	\N	\N	\N
319	99	27	117	D8527275	success	150.00	booking_reschedule	wallet	2024-06-21 17:23:58	2024-06-21 17:23:58	\N	\N	\N	\N
320	99	27	117	D1646849	success	0.00	booking_reschedule	wallet	2024-06-21 17:24:10	2024-06-21 17:24:10	\N	\N	\N	\N
321	99	27	117	D3117708	success	2125.00	booking_full	stripe_card	2024-06-21 17:25:00	2024-06-21 17:25:00	\N	\N	\N	\N
322	99	\N	\N	D1293482	success	80.00	wallet_transfer	wallet	2024-06-21 17:26:47	2024-06-21 17:26:47	97	\N	\N	\N
323	97	\N	\N	D4237740	success	80.00	wallet_receive	wallet	2024-06-21 17:26:47	2024-06-21 17:26:47	99	\N	\N	\N
324	102	7	120	D1025250	success	80.00	booking_advance	stripe_apple	2024-07-06 08:45:52	2024-07-06 08:45:52	\N	\N	\N	\N
325	86	2	20	D5105739	success	100.00	booking_advance	stripe_card	2024-08-16 15:32:37	2024-08-16 15:32:37	\N	\N	\N	\N
326	86	25	21	D1268520	success	50.00	booking_advance	stripe_card	2024-08-16 16:11:39	2024-08-16 16:11:39	\N	\N	\N	\N
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, dial_code, phone, phone_verified, password, email_verified_at, role, verified, user_type_id, first_name, last_name, user_image, user_phone_otp, active, remember_token, created_at, updated_at, role_id, device_type, fcm_token, device_cart_id, password_reset_code, req_chng_email, req_chng_phone, req_chng_dial_code, deleted_at, last_login) FROM stdin;
55	test s ss	tests@gmail.com	92	1234567123	0	$2y$12$cxtn.rBfqAuc.rRA.nDwS.VOYMMF687dEfdF9lZywX7AlnY9Trsy6	\N	\N	0	2	test s	ss	\N	1234	1	\N	2024-06-13 14:28:56	2024-08-17 18:11:22	\N	android	adgkduhuefabsfbagfafasf	cart_a	\N	\N	1234567123	92	2024-08-17 18:11:22	\N
25	Cristian Khan	ahmedkhan2134@gmail.com	971	8795214512	1	$2y$12$RudaDkEwPGXppFzKVnWqGeeP4j3uO1tkJqTpCXi7k1HH.LG0xzV.e	\N	\N	1	3	Cristian	Khan	666970c09549e_1718186176.png	\N	1	\N	2024-06-06 09:03:39	2024-08-17 18:14:55	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 18:14:55	\N
10	Eva Mular	chalent@me.com	971	526162947	1	$2y$12$wXk5hwByeI.drNdibNqjm.9hdBMqj2W7e5SH8NopoedgKexKoMvc.	\N	\N	1	3	Eva	Mular	666970d0c4695_1718186192.png	\N	1	\N	2024-05-22 14:13:45	2024-08-17 18:14:55	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 18:14:55	\N
27	Karen Valdez	a02@mailin.com	91	9876543234	1	$2y$12$ZitjzEGwa.cQNIJ1u.erd.QW788jRhVM6lFSwSgRckcOa7Q73.wAu	\N	\N	1	3	Karen	Valdez	66aa128b0185f_1722421899.jpg	\N	1	\N	2024-06-06 10:06:20	2024-08-17 18:14:55	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 18:14:55	\N
3	client 4	wahabfun22@gmail.com	971	69874512	1	$2y$12$LK80QeLVieYNSzpUVkFYle6.uoYklB3aAim432wmhD0RqYPTE98S2	\N	\N	1	2	client	4	\N	\N	1	\N	2024-05-01 11:59:10	2024-08-17 18:11:22	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 18:11:22	\N
64	test s2 ss2	tests2@gmail.com	92	4564564567	1	$2y$12$KDDi/2u3VefDIwoMY7e9u.Rqdp0Ml0sOV2IPKeXgJGXpPMRo1zry6	\N	\N	1	2	test s2	ss2	\N	\N	1	\N	2024-06-13 14:56:47	2024-08-17 18:11:22	\N	android	adgkduhuefabsfbagfafasf	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
103	test w	test@w.com	\N	05076630595	0	$2y$12$LMWazHXCDTgxUlx8hbrPmufDKErbVGI6hyi0nc66M6j.eyj1a0kFC	\N	1	1	\N	test	w	\N	\N	0	\N	2024-08-06 12:41:01	2024-08-08 15:28:18	1	\N	\N	\N	\N	\N	\N	\N	2024-08-08 15:28:18	\N
7	Maria Mollye	aliahmed000@gmail.com	92	6548741	1	$2y$12$m6bYiq7imYCkO4uA0X2BPelqULk8zjs7yiMryQ0Q1NkCtHwpoMvWm	\N	\N	1	3	Maria	Mollye	66b4b62d46ee1_1723119149.jpg	\N	1	\N	2024-05-06 02:17:44	2024-08-17 18:14:55	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 18:14:55	\N
16	suleman ali	ib2suleman.ali@gmail.com	92	3027655876	1	$2y$12$tZnakSGUL6OLVP7cKxDz.u/zbayHYgRZ3.PBsaam98i1clQMKftLC	\N	\N	1	2	suleman	ali	\N	\N	1	\N	2024-06-05 15:27:16	2024-08-17 18:11:22	\N	ios	\N	42032C14-7F9C-4CF2-A42F-4191441F658B	\N	\N	\N	\N	2024-08-17 18:11:22	\N
70	Suleman Ali	rdq3343xbvbg5x@privaterelay.appleid.com	\N	4700821332	0	$2y$12$lkmrKL60cJ3C4OQYy795Z.DXcQ5qIP3VFmPfzOTfVIR.gX/sfk6JW	\N	\N	0	2	Suleman Ali	\N	\N	\N	1	\N	2024-06-13 20:54:43	2024-08-17 18:11:22	\N	\N	\N	C938145D-E421-4A66-B2B3-0CD8BF774ADC	\N	\N	\N	\N	2024-08-17 18:11:22	\N
46	test four	test44@gmail.com	971	3690852123	1	$2y$12$RADx56MRXudBJ6Yre06vBOSF9edEEHakf1WNWDY/PSj8EzeRM9Vb.	\N	\N	1	2	test	four	\N	\N	1	\N	2024-06-12 08:40:11	2024-08-17 18:11:22	\N	android	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
32	n biswas	u5@mailinator.com	91	9832831811	1	$2y$12$El9NZB8cL29o3DMUJqCrr.haBzbYIddfIKonb2FHBAsZ/XD5zFLhK	\N	\N	1	2	n	biswas	\N	1234	1	\N	2024-06-10 12:45:41	2024-08-17 18:11:22	\N	ios	\N	cart_a	510ebd4fad5827aeb46968128a895639	\N	\N	\N	2024-08-17 18:11:22	\N
85	twenty two	u22@mailinator.com	971	9331423693	1	$2y$12$zx25.Xw7J2z7zqVC69pbV.SJUbADOGLvKR/VS0mE642S6eRjqEWV6	\N	\N	1	2	twenty	two	\N	\N	1	\N	2024-06-15 14:47:03	2024-06-15 16:49:48	\N	ios	c_V2iziWn07PqFUmKk-Y3A:APA91bEfAIXM9ZOkItDsBnm5Hi5Auo243Zn2eTYP72ozQCzi1Rxl4KQUFJKj8G0531a42u5uI-TvHGNTPI99bKdf9qMg1YTrQ2_B7DDw0ZcrED_ajD9htbgk-QXIVU6c_CSt-BMhEcJS	C1049C2F-15F6-4C70-A3B8-6DAA107E74A3	\N	\N	\N	\N	2024-06-15 16:49:48	\N
76	n seventeen	u17@mailinator.com	971	933258239633	1	$2y$12$ZKNb60bEreashAQG4xRkt.c7AJaa6mVvf.xsvY9IMdeaKMNmljdyK	\N	\N	1	2	n	seventeen	\N	\N	1	\N	2024-06-14 08:46:06	2024-08-17 18:11:22	\N	ios	\N	23A6D2A6-1017-4297-B125-FABEFB6200DF	\N	\N	\N	\N	2024-08-17 18:11:22	\N
15	maaz siddiqui	maaz@test.com	92	5412778	1	$2y$12$sVEKoTQcXZLdaRKqCpTxhe5ni5ImHUVVqr6wABcmEJKd0gUt2rkTm	\N	\N	1	2	maaz	siddiqui	\N	1234	1	\N	2024-06-03 17:56:06	2024-06-15 13:15:57	\N	android	\N	cart_a	1e393c2695be25a4d74ea99f1e978c1d	\N	\N	\N	2024-06-15 13:15:57	\N
95	chalent chanter	x95y47zzfm@privaterelay.appleid.com	\N	8550831493	0	$2y$12$XHwGaI8Ns/8q2.EWfLvnsuKiFhi037pIgn24fhHykVIwa48CljBwS	\N	\N	0	2	chalent	chanter	\N	1234	1	\N	2024-06-17 10:42:17	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	526162947	+971	2024-08-17 18:11:22	\N
106	wahab khuran	abwahab@gmail.com	\N	03994643704	0	$2y$12$.mOhYyHMz5grXgmMKhC6.OGHTxaapJJL0JGFkvYamZqjnI1e3mQi6	\N	1	1	1	wahab	khuran	\N	\N	0	\N	2024-08-10 20:06:14	2024-08-17 17:37:00	1	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:37:00	\N
8	chalent chanter	alan@gmail.com	\N	09333773249	0	$2y$12$AudZhq09j03ll1KFsRAQFO8RWbgr5RxB.eDNDLJOejLF5oTwRN776	\N	1	1	1	chalent	chanter	\N	\N	0	\N	2024-05-07 14:27:41	2024-08-17 17:37:08	3	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:37:08	\N
108	asd a	asd@gmail.com	\N	05516907479	0	$2y$12$9LDYhgyKYkt10xp4ZNgMUerIMOx36OIeJWPacemva6Bcjx7AKryUi	\N	1	1	\N	asd	a	\N	\N	0	\N	2024-08-12 08:16:38	2024-08-12 08:19:21	3	\N	\N	\N	\N	\N	\N	\N	2024-08-12 08:19:21	\N
6	client 2	abwahab22@gmail.com	971	6548451214	1	$2y$12$Q94mUPsqok5uZEeV49023.IvKrBPuvdAnW54HlBPs7l91F2JwdMAO	\N	\N	1	2	client	2	\N	\N	1	\N	2024-05-06 02:11:24	2024-08-17 18:11:22	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 18:11:22	\N
34	anil navis	ab@ab.com	971	54649846	1	$2y$12$FZQW3XPQhgwZZSSfa2DYiegjDsatKxmsdVcpNCyPDFsoD1W/hHniy	\N	\N	1	2	anil	navis	\N	\N	1	\N	2024-06-10 21:25:30	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
42	suleman ali	suleman.ali303+12333@gmail.com	92	2345412362	1	$2y$12$S2LXfpFssqtXFwUnVN4gEuX5XOAC8Y8CsmZhuN.d0jTcgiaIpmU66	\N	\N	1	2	suleman	ali	\N	\N	1	\N	2024-06-11 15:38:46	2024-08-17 18:11:22	\N	android	testing22	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
67	suleman ali	rdqxbvbg5x@privaterelay.appleid.com	971	123456789123	1	$2y$12$Jtqy2b/Kqi2pSEq/W7udTOtfSMU2fUuKPuqopLcdsE1UiuK7MM7aG	\N	\N	1	2	suleman	ali	666bed09458f2_1718349065.jpg	\N	1	\N	2024-06-13 20:39:13	2024-08-17 18:11:22	\N	ios	\N	3A4C648B-4C55-420C-96FC-DF48DDB906CC	\N	\N	\N	\N	2024-08-17 18:11:22	\N
79	ne bus	u19@mailinator.com	971	9633221712	1	$2y$12$Jugq7M9PkWUw66C1xrykPO1Upzua8jCIOAuoTa6ExEKwY2FzTFUxS	\N	\N	1	2	ne	bus	\N	\N	1	\N	2024-06-14 10:46:46	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
26	test one	test@gmail.com	92	123123123	1	$2y$12$pp59bHubFu3zcg2mK8bpSeoFaGtgp49nEFuxSH.oXEUa5GxONSjLy	\N	\N	1	2	test	one	\N	1234	1	\N	2024-06-06 09:36:56	2024-08-17 18:11:22	\N	ios	\N	cart_a	883127576db3d356872f0c6b4765277a	\N	\N	\N	2024-08-17 18:11:22	\N
88	twenty three	u23@maklinator.com	971	96185586663	1	$2y$12$IAqBQk79bJNf7WFb7WSKuuQ67999iWxRB3AAkU1cSRMXKyNmuM8yO	\N	\N	1	2	twenty	three	\N	\N	1	\N	2024-06-15 17:00:22	2024-08-17 18:11:22	\N	ios	c_V2iziWn07PqFUmKk-Y3A:APA91bEfAIXM9ZOkItDsBnm5Hi5Auo243Zn2eTYP72ozQCzi1Rxl4KQUFJKj8G0531a42u5uI-TvHGNTPI99bKdf9qMg1YTrQ2_B7DDw0ZcrED_ajD9htbgk-QXIVU6c_CSt-BMhEcJS	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
41	n biswas	u8@mailinator.com	971	9335742666	1	$2y$12$KGImCLhgv6elEWZy4zoVxeIyWfVSRF/37rnKcl4tJ6Nu6FfSbfTHW	\N	\N	1	2	n	biswas	\N	\N	1	\N	2024-06-11 14:42:13	2024-08-17 18:11:22	\N	android	\N	0fa76acc736e0cd2	\N	\N	\N	\N	2024-08-17 18:11:22	\N
31	asdf nazir	asad@gmail.com	971	3441560320	1	$2y$12$Hba8wUi/YtO3qSY7m95ta.qign66hMGsrE5bEwTS5G4qoyKupAMCe	\N	\N	1	2	asdf	nazir	\N	\N	1	\N	2024-06-07 13:22:16	2024-08-17 18:11:22	\N	android	ad	7812fe4f9424cf17	\N	\N	\N	\N	2024-08-17 18:11:22	\N
2	Anya Korla	wahabartist@gmail.com	971	23423423423	1	$2y$12$eW/BHBwfTidVnAxCRpbf6.cDwvlo/zGk07ApUvOcRetpeePESmVbi	\N	\N	1	3	Anya	Korla	66bdc175ce74c_1723711861.jpg	\N	1	\N	2024-04-25 04:47:10	2024-08-17 18:14:55	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 18:14:55	\N
107	anil navis	anil@admin.com	\N	09375804324	0	$2y$12$p0iqBytjTx25F5VOz6KtDOKkof5CRU4.n/AcmecSbXI./mnmXWd5W	\N	1	1	1	anil	navis	\N	\N	0	\N	2024-08-10 20:09:22	2024-08-17 17:36:32	3	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:36:32	2024-08-10 16:10:37
14	maya d	maya@gmail.com	971	9578861300	1	$2y$12$qo7mPf3QXCxub8nYS7vtLuGjldUzU1FwpK9HRt7QUSsutRmROXbfy	\N	\N	1	2	maya	d	\N	\N	1	\N	2024-05-25 07:53:27	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
12	abdul wahab	abwahab232@gmail.com	92	5412364	1	$2y$12$FLrBlWT1opIH74z358O8YuBuYWYL4EAvQrDM35iSrIX5Y9KpRjeVe	\N	\N	1	2	abdul	wahab	\N	\N	1	\N	2024-05-24 05:59:47	2024-08-17 18:11:22	\N	android	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
36	nemai biswas	u7@mailinator.com	971	934367976	1	$2y$12$Z4GPegc.UvT95w4TcheKwuMf1roeniIlKWFnLMgl.caRm6uQN/7ai	\N	\N	1	2	nemai	biswas	\N	\N	1	\N	2024-06-11 12:45:07	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
37	anil navis	bb@bb.com	971	546464664	1	$2y$12$pBCZbRnZWjNFs4qqycfdAOTvrzUirtAd7XZauuyUwYd75Dbv/Q6V.	\N	\N	1	2	anil	navis	\N	\N	1	\N	2024-06-11 13:02:35	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
38	suleman ali	suleman.ali303+123@gmail.com	92	1233232323	1	$2y$12$ECj0CuKMDVlJFKMytHodDeOPxTiFU7v3209a8P/FnVwSlmpUn5IfW	\N	\N	1	2	suleman	ali	\N	\N	1	\N	2024-06-11 13:09:49	2024-08-17 18:11:22	\N	android	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
39	anil brother	anil@gmail.com	971	12344322	1	$2y$12$HP8pUw0BZZZCOMiQPMxbweQFGyB4CxdzIQmnA5qByVeYCjY0vj8ee	\N	\N	1	2	anil	brother	\N	\N	1	\N	2024-06-11 13:32:06	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
40	anil2 brother2	anil2@gmail.com	971	1231231231	1	$2y$12$DLCzaWmVCKEdpTrVwcvT.ukL5UXPdGjUqtIe.hWZUI4tJjDlpF1Jm	\N	\N	1	2	anil2	brother2	\N	\N	1	\N	2024-06-11 13:37:53	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
48	anilfgv navis	vying@v.com	971	5464649422	1	$2y$12$YMjHs5yhJ77kwZn7kfxk1OfEH4m7axdAXE3zVWTID/ec95oSvSl3y	\N	\N	1	2	anilfgv	navis	6669ce9024e5f_1718210192.jpg	\N	1	\N	2024-06-12 10:18:23	2024-08-17 18:11:22	\N	ios	\N	FF0BE3A4-93C7-4C5A-AEBE-73C35691299D	\N	\N	\N	\N	2024-08-17 18:11:22	\N
68	test social e	testingfordxb@gmail.com	971	369369581	1	$2y$12$jX.iR8ot2FjCegwMINxumeVNTRH77iuEkd/zh2qPlGDg.mXp4grJC	\N	\N	1	2	test	social e	\N	\N	1	\N	2024-06-13 20:48:59	2024-08-17 18:11:22	\N	android	\N	507c25ffbc01d8ae	\N	\N	\N	\N	2024-08-17 18:11:22	\N
4	client 3	alieahmed@gmail.com	92	5412362	1	$2y$12$.JRH8NQ3gh36qD7iesk9sOzVqE0FyqCZUzBI0k..6hWdhMRzZs8nG	\N	\N	1	2	client	3	6666aea38ed5e_1718005411.png	\N	1	\N	2024-05-03 13:57:18	2024-08-17 18:11:22	\N	android	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
51	anil na	nm@nm.com	971	5464648946	1	$2y$12$6.VPMAWFonF7YLEyTbvkv.sOZhYpnz9qZldq39n2d6VPNpIRTwCmq	\N	\N	1	2	anil	na	\N	\N	1	\N	2024-06-12 21:45:00	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
52	suleman ali	ib2suleman.ali3032@gmail.com	92	3027655333	1	$2y$12$B8XL/ChOlNJiB3/JCeBeBO2ERjA3shWnJOvhRvJoSbTTL2a3hSbDK	\N	\N	1	2	suleman	ali	\N	\N	1	\N	2024-06-12 22:06:35	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
56	nemai fourteen	u14@mailinator.com	971	9322542696	1	$2y$12$PXNqfcRtJT9PEzQwQlIPw.j2Eti.vQ8JeynkmuBA1QRqOTZWep4za	\N	\N	1	2	nemai	fourteen	\N	\N	1	\N	2024-06-13 14:38:26	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
65	anil mavis	bbb@b.com	971	546464964	1	$2y$12$mr8QM1Cz4Ib3hym/tm09zOL4kqK0mcQkox8C8pIxnj3cQ5R.3UT3.	\N	\N	1	2	anil	mavis	\N	\N	1	\N	2024-06-13 15:06:40	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
80	nemai social	nbexam.mail@gmail.com	971	93247236966	1	$2y$12$LMRohtARilOCncUCRxI2Rek8.yf3gXIlkMAsuSHCwrF734K6PiKlG	\N	\N	1	2	nemai	social	\N	\N	1	\N	2024-06-14 10:50:45	2024-08-17 18:11:22	\N	android	\N	0fa76acc736e0cd2	\N	\N	\N	\N	2024-08-17 18:11:22	\N
83	nemai iossocial	nemai.biswas56@gmail.com	971	963852123698	1	$2y$12$xVPyDwz7Rgw9Emlzw0vOOud7QVcy2cYSjMFlj0Kantqk9kKUi.tG6	\N	\N	1	2	nemai	iossocial	\N	\N	1	\N	2024-06-14 15:44:55	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
77	nema buswas	nemai@dxbusinessgroup.com	\N	3638389678	0	$2y$12$iTtSuvhwTelGjmuzdhqRbua6Fu7XUDSALQEdgtWRzorTYjJyxDOCe	\N	\N	0	2	nema	buswas	\N	1234	1	\N	2024-06-14 08:48:41	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	9321742366	+971	2024-08-17 18:11:22	\N
54	abdul wahab	abwahab_social@gmail.com	92	26547892	1	$2y$12$bw4A/xlwDFUN8rbUOf360uzRE4mPuHZXofMJTvajbz1BqpbZyY.uu	\N	\N	1	2	abdul	wahab	666a9e6619458_1718263398.png	\N	1	\N	2024-06-13 11:15:07	2024-08-17 18:11:22	\N	android	adgkduhuefabsfbagfafasf	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
47	test e five	test5@gmail.com	971	369085216464	1	$2y$12$atB77eLEX8N.RO90HyR4QOklwkqAjw7I./9Xq00fJAkytCiDYkcG2	\N	\N	1	2	test e	five	66696e972d1e1_1718185623.jpg	\N	1	\N	2024-06-12 08:57:35	2024-08-17 18:11:23	\N	android	\N	507c25ffbc01d8ae	\N	\N	\N	\N	2024-08-17 18:11:23	\N
45	n biswas10	u10@mailinator.com	971	93346636219	1	$2y$12$d5lI6Oz8QuYrKbUfk1maWeVDgpXqN6GEiNujaK6c01PNkblaRopu6	\N	\N	1	2	n	biswas10	\N	\N	1	\N	2024-06-12 06:42:26	2024-08-17 18:11:22	\N	android	\N	0fa76acc736e0cd2	\N	\N	\N	\N	2024-08-17 18:11:22	\N
104	rt sdf	dsz@gmail.com	971	567567556	1	$2y$12$sogf52BUES4IBbv5kQsPnORJXVzn3t8fie2L86YxIlgfQAA04wFGO	\N	\N	1	3	rt	sdf	66b396682e54f_1723045480.jpg	\N	1	\N	2024-08-07 19:44:40	2024-08-14 17:51:06	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-14 17:51:06	\N
11	sasha skorniakova	chalent.chanter@gmail.com	\N	08937691468	0	$2y$12$Ablg.aAJS/Xuub8raOHos.5oxTrALul9U3fOcKi.rZsXDafQVa.HC	\N	1	1	1	sasha	skorniakova	\N	\N	0	\N	2024-05-22 16:28:47	2024-08-17 17:37:15	2	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:37:15	\N
13	ashwin a	ashwin@gmail.com	971	9578861342	1	$2y$12$eMu61KdFBhFU2PCLoe7tzueeNSgsFC1o1vGyEsO8/3QKdqCu5q.mu	\N	\N	1	2	ashwin	a	\N	\N	1	\N	2024-05-24 11:42:34	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
28	nemai biswas	u0@mailinator.com	91	9663993325	1	$2y$12$pagJ2RL4ZYqd6/MRPajq3u1PSyUxcIUphN.OTwnk23Qb23iE.BxwS	\N	\N	1	2	nemai	biswas	\N	\N	1	\N	2024-06-06 10:32:14	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
49	anil navis	v1@v1.com	971	546464864	1	$2y$12$mwbyRiB34fIEnM97QuyJw.xKcPBRe1KoBlMqUixhmMLVOJBnU9yVC	\N	\N	1	2	anil	navis	\N	\N	1	\N	2024-06-12 10:39:46	2024-08-17 18:11:22	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:22	\N
44	anil naviss	an@an.com	971	453464865	1	$2y$12$6vQqtVicunWuFTu3gq2Ise8.19nHOLdrQz6B3c1Os9isnXPu1evby	\N	\N	1	2	anil	naviss	\N	\N	1	\N	2024-06-11 19:50:52	2024-08-17 18:11:23	\N	ios	\N	52A48D12-0C2D-40E8-B32E-FF5C95376A22	\N	\N	\N	\N	2024-08-17 18:11:23	\N
33	test three	test3@gmail.com	971	12369807412	1	$2y$12$.IDU8Mp92Q7xp5Q3cZCdROve9FmfU64pebF/IccrKq7BB6pZSYvWa	\N	\N	1	2	test	three	\N	\N	1	\N	2024-06-10 21:06:20	2024-08-17 18:11:23	\N	android	\N	507c25ffbc01d8ae	\N	\N	\N	\N	2024-08-17 18:11:23	\N
29	test two	test2@gmail.com	971	369369369	1	$2y$12$OLri6yEYnvO2.b5H8T.KoexwHrDnS3EiVpVDjrawL2Svdo5SB7eAK	\N	\N	1	2	test	two	\N	1234	1	\N	2024-06-06 12:31:52	2024-08-17 18:11:23	\N	android	du8wYzH-QOizenGm7aaHk_:APA91bGJNSE_Kr2ZU9PofJmILKg_Qn5xCo2b5sg5vqWH72EOCipIWnn0CiaS3hbZ9j2JH7MLjVk4Zpu73_MBCsq184Vz_BjzftTwiS8k25LJoQTL2DQ8maWjsWXXaXQll6nqD8bp58Fi	507c25ffbc01d8ae	fe95b9a47d51540bcb74937e4ac27d69	\N	369369368	971	2024-08-17 18:11:23	\N
109	qwerty y	qwe@yp.com	971	6785675675	1	$2y$12$LJyjwTSv7p05Km5k.PB3i.cwfAHsfrY/rVRdrmau4fkbpPbYRWYre	\N	\N	1	2	qwerty	y	\N	\N	1	\N	2024-08-14 12:29:51	2024-08-17 18:11:23	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 18:11:23	\N
66	nemai fifteen	u18@mailinator.com	971	6316996996	1	$2y$12$SBeGgsrR2AL1rNp0bFbz.OcIHb59uOmbl7OPM9giwB3IdsgzB0e5y	\N	\N	1	2	nemai	fifteen	\N	\N	1	\N	2024-06-13 16:33:23	2024-08-17 18:11:23	\N	android	\N	0fa76acc736e0cd2	\N	\N	\N	\N	2024-08-17 18:11:23	\N
97	twenty seven	u27@mailinator.com	971	931742366633	1	$2y$12$bdLbEc7.ce/6Uzuwx3jzvOm.jCfPNpD9gJIBQR9./Dbc4e99jgI1y	\N	\N	1	2	twenty	seven	\N	\N	1	\N	2024-06-20 18:49:59	2024-08-17 18:11:23	\N	ios	eYEvT9q-3kwogtkL7zBTQ7:APA91bHgr-TZZDMZbG51-9Rw8H657Uu9UQZY3Vl4Hi6bCvYpvhYWXVIyJs1AJCfgVdhC1EZELEOZd6xlI0XwLxj2qjBfPyoGH4H2ioW0DaxL-uwCuSQykqxkzHM8LW1XXAIIru8DGuyp	8F4563D8-C510-4B1A-AD7F-D85E0AB727FB	\N	\N	\N	\N	2024-08-17 18:11:23	\N
69	Test S2	tests3@gmail.com	\N	6406186222	0	$2y$12$xTwsfvWJpHiL.QKj1maKcu1Nuk9iH56u4x/nU3mwi5/wlUhl8yzOS	\N	\N	0	2	Test S2	\N	\N	\N	1	\N	2024-06-13 20:53:10	2024-08-17 18:11:23	\N	android	adgkduhuefabsfbagfafasf	cart_a	\N	\N	\N	\N	2024-08-17 18:11:23	\N
81	test s2 ss2	tests4@gmail.com	\N	2116852814	0	$2y$12$OQcSQ/L1kNGBod4GI4DBt.dLJ860hVP4PCoCZjRxr4buJZmKyEQ2O	\N	\N	0	2	test s2	ss2	\N	1234	1	\N	2024-06-14 10:55:38	2024-08-17 18:11:23	\N	android	adgkduhuefabsfbagfafasf	cart_a	\N	\N	3095565455	92	2024-08-17 18:11:23	\N
84	nemai twenty one	u21@mailinator.com	971	96385274123	1	$2y$12$WZ/Byv0snv9BSGmUPWLcCe43qHaOsj8lJckMp.rM8kpRPnwCSr6r2	\N	\N	1	2	nemai	twenty one	666c7c60ca27f_1718385760.jpg	\N	1	\N	2024-06-14 20:36:38	2024-08-17 18:11:23	\N	ios	fGOIjzfHZENgsScfPMpMK6:APA91bFMEzuqdLqFXMV1GQf1KgNJV2HuS-jA3OPsjlc8dArx6u3BpWKALuKMO9dtgMtzdnWeRlWLqO5EtdOEev-9_aB6IkLEqXKdlzNMGfFuyJP1sVMXz4mMxjjvqO5YK-ikktHQo3Ix	F012F066-0585-4A6C-A860-B6128EAB96A1	\N	\N	\N	\N	2024-08-17 18:11:23	\N
100	michael richards	u30@mailinator.com	971	93217536996	1	$2y$12$K1NiGVOvLbFAWRbiYEmwq..6jv4oimBaGHj5OX.slkyJyntArfQry	\N	\N	1	2	michael	richards	66885d892d11c_1720212873.jpg	1234	1	\N	2024-06-21 17:20:54	2024-08-17 18:11:23	\N	ios	\N	25644745-0E54-4959-B749-1D786E4E4985	\N	michael@elsorted.com	\N	\N	2024-08-17 18:11:23	\N
96	twenty six	u26@mailinator.com	345	9433469966	1	$2y$12$z.wEs/0wkKfzrhDCEHiTyOL1JSjv0cF/WqZNkt43xIkf5fNALCH1e	\N	\N	1	2	twenty	six	\N	\N	1	\N	2024-06-20 14:13:16	2024-08-17 18:11:23	\N	ios	cMEcXOSBXULZvOcjunSCRw:APA91bG9neCmBOFABJr613Q_SIOKdMun9nwWdBeNxg-UnSnqgesmQzU3KLPBElAlTQdo1VTXB42-ogDeAmGNE6KCCU90DsBSFjRnNQ5PIMxpZ95ScBrRcgRe5nkShVZDpSjXvF923ajT	cart_a	\N	\N	\N	\N	2024-08-17 18:11:23	\N
105	asd f	sd@gmail.co	\N	07725165624	0	$2y$12$WDrgKcGMISRIkW6bUaUvDe.SCZ2BsY8E6xVYY0KmEgMdKiNdrCpJK	\N	1	1	\N	asd	f	\N	\N	0	\N	2024-08-08 15:35:14	2024-08-08 20:51:06	3	\N	\N	\N	\N	\N	\N	\N	2024-08-08 20:51:06	\N
63	abduls2 wahabs2	abwahab_social2@gmail.com	971	5261494881	0	$2y$12$N8NyNpUpkanyS2PUQTcfceYDa11Xs.74HrbVtX2wAAcd2qjDv/CAy	\N	\N	0	2	abduls2	wahabs2	666ad06eb783e_1718276206.png	1234	1	\N	2024-06-13 14:56:36	2024-08-17 18:11:23	\N	android	adgkduhuefabsfbagfafasf	cart_a	\N	\N	26547894	92	2024-08-17 18:11:23	\N
78	regan ali	rehan@ali.com	971	3086523185	1	$2y$12$gr2Q5twUBbFAWddY8GMTAevK.nDmVeddWKUyiGE83MVg/pHczluOa	\N	\N	1	2	regan	ali	\N	\N	1	\N	2024-06-14 10:40:31	2024-08-17 18:11:23	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:23	\N
87	muhammad maaz	maaz@test1.com	971	54248454844	1	$2y$12$XtxpbjTL8NIbmUAy8oJw4ep88LQXjpc1p2SJMgM2svcQRA1Q/50La	\N	\N	1	2	muhammad	maaz	\N	\N	1	\N	2024-06-15 15:36:00	2024-08-17 18:11:23	\N	ios	eRqp2jCWTUIWrhAnLu-u1i:APA91bHPkjJt18uiRDa_L8JlcG6gMc4nPyOCwDKga3gShjFgbvnTbd3xM-WYeNlRp0gbnGaf-Gtjw30FNTKG4tTXeHLV86GsVzlW9m0eUcpysu8v0JExjJavB8kvbscL-MXw99kztrAh	C35F6CF4-527F-4B1E-9D39-C9C0FE83F5B0	\N	\N	\N	\N	2024-08-17 18:11:23	\N
89	twenty four	u24@mailinator.com	971	9314723666	1	$2y$12$wdFzSan3svpAjcoWSDanpup4VbabV2Hsmgb/abu6F3ES5nGWuooXS	\N	\N	1	2	twenty	four	\N	\N	1	\N	2024-06-15 20:57:40	2024-08-17 18:11:23	\N	ios	dJEfeUPrSEJPnKYK4Q8EKO:APA91bHga201phDPVhgrxySylH07Kk14szZQ65ZqsatVBvgXIFicvMu_rSW7j1c47ssWKmU6ss0VP25DOLiKlVLNvSY6_7Xgi0aZpMZ-GqeWI_172YswK3Fe4VYddoDqP7OcXiHJn4RJ	42032C14-7F9C-4CF2-A42F-4191441F658B	\N	\N	\N	\N	2024-08-17 18:11:23	\N
92	chin tonga	u25@mailinator.com	971	96385215755	1	$2y$12$Ivn/f8Bpzr5UZdxTl8S8v.ovulq8dwXTZqBmy21VWXg0ZQaXhpTJq	\N	\N	1	2	chin	tonga	666ff3ade7e2c_1718612909.jpg	\N	1	\N	2024-06-16 10:59:35	2024-08-17 18:11:23	\N	ios	dY4uh9b_xUZVniGUuXjyFJ:APA91bFJaqUaOuXinOybzWsUyw0G-h2_M0mIzDLuKk2zSF-IfCLmgQhixsTvwrc055QUxzsEiRRlTd0c-RpeEF1mAB7I-oSCwS8lCQry-FcFiessaIWLI0_pdcroKqEmwRjLLLT0wT7u	9DD1952B-5326-421E-8057-4FA1C9B64C8C	\N	\N	\N	\N	2024-08-17 18:11:23	\N
98	twenty eight	u28@maolilinator.com	971	93354726699	1	$2y$12$QCgbfh6/4uICMA7IC7w7ze1J0id.FAjk59KAdJRNdWOEoO734L1Vq	\N	\N	1	2	twenty	eight	\N	\N	1	\N	2024-06-21 13:56:51	2024-08-17 18:11:23	\N	ios	dD7JVZ9-LEiDp4lr__b9om:APA91bElWlQOC4T3qP45NCVwOTJ8fgrX91fsN8gW8cH05KNMHruUgnTypZnx0LTurS6IJVelJXeKg5M5m7bwIvLFFHVvCnVrroPHdWt0NYPHLrlUdOSbkShaACJ9SPEhCUgYoack3e_i	cart_a	\N	\N	\N	\N	2024-08-17 18:11:23	\N
99	twenty nine	u29@mailinator.com	971	93214136963	1	$2y$12$C46dns8Zc2PPDypcY1hTmueTiwfp8Itl9Z7i5jMGKIBasmfAOe0Y6	\N	\N	1	2	twenty	nine	\N	\N	1	\N	2024-06-21 15:42:07	2024-08-17 18:11:23	\N	ios	czk5ZX9XeUiohYZWxfg_ZS:APA91bHPP2FulsOplnbbfK6Lt6VJicvR-j_EUBE9M2g_hHzSKmME0LZMVWO0oHJOPyl4VwcIGhoPfEtFj_NLjfaTLyz4tOy-wWRE9zff-FDXBgfUaBVM2Z_iSUO7Fxv__2o7jcSubCpN	09A996F8-E699-4A5A-A0F6-D2F2FF9FAB35	\N	\N	\N	\N	2024-08-17 18:11:23	\N
102	rusvin s k	rusvinmerak@gmail.com	91	7034526952	1	$2y$12$3bQxVYmLnltHiemBB.CHsONXkxuJ4SXYaqL1mgmic0d3o4SDL0476	\N	\N	1	2	rusvin s	k	\N	1234	1	\N	2024-07-06 08:37:59	2024-08-17 18:11:23	\N	android	\N	cart_a	627d52d5829c734e0580355bc0fa2463	\N	\N	\N	2024-08-17 18:11:23	\N
50	nemai eleven	u13@mailinator.com	971	96385274126	1	$2y$12$P8gHJnB6V1oNpiAzBGGT/.eydqmFsYJvCS.q4oyOvYEintEMkeaS6	\N	\N	1	2	nemai	eleven	666a75fb1bb9b_1718253051.jpg	1234	1	\N	2024-06-12 21:41:11	2024-08-17 18:11:23	\N	ios	\N	BD8030DA-0D27-4FA5-956E-846B6F429E24	\N	u12@mailinator.com	\N	\N	2024-08-17 18:11:23	\N
57	test s1 ss1	tests1@gmail.com	92	112211221122	1	$2y$12$QRa066TDJSS8ril6ZIOYMuRvKGjtNwVAz2IFROaYOpMzLkBJ6TLES	\N	\N	1	2	test s1	ss1	\N	\N	1	\N	2024-06-13 14:39:41	2024-08-17 18:11:23	\N	android	adgkduhuefabsfbagfafasf	cart_a	\N	\N	\N	\N	2024-08-17 18:11:23	\N
43	taiwan khan	a@a.com	971	3000000000	1	$2y$12$xY4nSatJ02geqTScBRKRB.bDYdiQnHWlhmXrI1tLste6yFb1qbjnq	\N	\N	1	2	taiwan	khan	\N	1234	1	\N	2024-06-11 17:25:21	2024-08-17 18:11:23	\N	ios	\N	DC5E2CC9-9D8C-42A0-B39C-4EA56BE2DE16	9dac05964bae97c303ef5e21d4944b68	\N	\N	\N	2024-08-17 18:11:23	\N
93	did bdbd	hb@hb.com	971	5464484664	1	$2y$12$Vr.Np0msQ0iZ0scw2QldKO6BvM9MREQOtHoHODKVy8bRh8ExMjC9.	\N	\N	1	2	did	bdbd	666e96bde5c87_1718523581.jpg	\N	1	\N	2024-06-16 11:36:15	2024-08-17 18:11:23	\N	ios	fjcD4AFTxUrjgxx1pzGtOC:APA91bE4Xo94XjIdxfOXDv64Ho2G4BoNQt-2a0KHDWh3YNvqxKrGswwA2hEFaBrOJrnHGh6dizOuHaUHwhSYA82tSoouyJz5cETBqtfQ0Qzx9OdVmJQT2PjjMFodakX4t3x35Vi1wXDD	cart_a	\N	\N	\N	\N	2024-08-17 18:11:23	\N
53	nemai thirteen	u13@mailinatot.com	971	9638527412	1	$2y$12$T8poWbXNZm/NHjWBDHKA9ujIlko.2Pk.aDuunFUtBxLB/YJ/irdPG	\N	\N	1	2	nemai	thirteen	\N	\N	1	\N	2024-06-13 10:09:05	2024-08-17 18:11:23	\N	ios	\N	cart_a	\N	\N	\N	\N	2024-08-17 18:11:23	\N
1	Admin	admin@admin.com	971	112233445566778899	0	$2y$12$jZpOYWvjZa3lrzTRjRkfxuvfwrVCiZWu1YKNH1Uzu.Kz9Sh6jDO4e	\N	1	1	1	\N	\N	\N	\N	1	\N	\N	2024-08-21 15:23:51	\N	ios	\N	25644745-0E54-4959-B749-1D786E4E4985	\N	\N	\N	\N	\N	2024-08-21 11:23:51
94	ajesh kumar	ajeshcd@gmail.come	971	5050418601	1	$2y$12$Dd/ooxk8mMfdxHFtD2DrwOTERXi0isSbQwoLHIR4K34qBWFbKeM3u	\N	\N	1	2	ajesh	kumar	\N	\N	1	\N	2024-06-16 11:52:00	2024-08-17 18:11:23	\N	ios	cWVaEbU_zUzzp3nAkw8MWV:APA91bFHvZ7IrI5Tjl4YRslRPpmEQ98T3IBc7VRnRUy2F2ZOdrNm8SjGk9gkV1kqc6nUte6OI4qqqc9eicRq88YF4pE4p5qqtigcyF9UgjMXzkZPBw4KZCm6bJiyDXaNpHPtQBjjjFFY	cart_a	\N	\N	\N	\N	2024-08-17 18:11:23	\N
9	client 1	ib2suleman.ali+201@gmail.com	92	3027655878	1	$2y$12$jbd9SxAgIV4XQDEz8mUkb.2yHg9OHSR27SQvSdBcM/tzxAXVqvh26	\N	\N	1	2	client	1	6667f296d88b9_1718088342.jpg	\N	1	\N	2024-05-22 08:06:18	2024-08-17 18:11:23	\N	ios	\N	42032C14-7F9C-4CF2-A42F-4191441F658B	f725a5de9853ae72de9cbf279143f1eb	\N	\N	\N	2024-08-17 18:11:23	\N
30	nemai b	u1@mailinator.com	92	9333669963	1	$2y$12$uXAt4nH6mlr7wXmn5HYvFOCZrXo5LWL8KKako5LUzicHNVrze.saW	\N	\N	1	2	nemai	b	666bf0fe150b4_1718350078.jpg	1234	1	\N	2024-06-06 17:53:26	2024-08-17 18:11:23	\N	ios	f09ILUMlbkQdmUW8_9kSjZ:APA91bFdsEMcTlst__4QWAKAAWNjJ52D1Dl3iWYcv6Uwaxpo6Q9YL69fGOK7C29HSo9Shsol4tGxAoTUnzezRSZy8QaK-kNOxryG30uBPHs1rnR-zb8MajyqVqiefFlW-fssK3qTeekk	EFBE8EE9-6B81-42FA-BCE1-E86DF9ACEC46	c361086c12ff73e06ec5ca74d5be9e82	\N	933366996	+92	2024-08-17 18:11:23	\N
112	se r	fr@gmail.com	\N	03551647407	0	$2y$12$ehNE5TVHpGqtOKn/vmweaujRF0zxOy1QJ5InWyhh7h01bKLn0kWVG	\N	1	1	\N	se	r	\N	\N	0	\N	2024-08-14 15:26:41	2024-08-14 15:26:58	3	\N	\N	\N	\N	\N	\N	\N	2024-08-14 15:26:58	\N
113	xd dsrf	sdf@gmail.com	\N	08109562674	0	$2y$12$IMlPrweZpo6NS/mHqzovqOpi2Ewb6WFly72qV8rtUV3u/fue5vSOK	\N	1	1	\N	xd	dsrf	\N	\N	0	\N	2024-08-14 15:28:17	2024-08-14 15:28:27	3	\N	\N	\N	\N	\N	\N	\N	2024-08-14 15:28:27	\N
131	Wahab khurram	wahabfun22@gmail.com	971	134234234323	1	$2y$12$dQqjHwGNaRx.Tq7iUxCXrOn5CGlDCS.c9I9/RYtW6lJY3SuAGU6py	\N	\N	1	3	Wahab	khurram	66c0eb3915f93_1723919161.jpg	\N	1	\N	2024-08-17 22:26:01	2024-08-17 22:28:39	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 22:28:39	\N
114	asd ad	sad@gmail.com	\N	09548207723	0	$2y$12$Mvx5nqxxmMFZtTjSFXwp6OVSdxk5RqiQV.tLKR.BSQUNJqqF8nYLK	\N	1	1	\N	asd	ad	\N	\N	0	\N	2024-08-14 15:29:27	2024-08-14 15:29:37	3	\N	\N	\N	\N	\N	\N	\N	2024-08-14 15:29:37	\N
111	ahmed ali	ahmedali@gmail.com	971	65646131	1	$2y$12$Wke9LzYf3X1ZcSzlE0HE.eYV9Rcn03UMc7SibvfEX/vSj/LLeLGTm	\N	\N	1	2	ahmed	ali	66bc7863a5514_1723627619.png	\N	1	\N	2024-08-14 13:27:00	2024-08-14 15:34:21	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-14 15:34:21	\N
127	abdul wahab	abdulwahab22@gmail.com	\N	05470799872	0	$2y$12$OMaGv31t1SPWP.U5GmxBB..gQWNxCbvLj.5KB30VvMb17lfERWC96	\N	1	1	1	abdul	wahab	\N	\N	0	\N	2024-08-15 15:00:07	2024-08-17 17:36:38	3	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:36:38	\N
116	zvz fdgd	dfg@gmail.com	\N	07908025523	0	$2y$12$h/8iEjvGummEM4XmsF8zDuz7gPpQqypzQJVATXA.EDHUp/u.sRQ6y	\N	1	1	\N	zvz	fdgd	\N	\N	0	\N	2024-08-14 15:41:05	2024-08-14 15:41:18	3	\N	\N	\N	\N	\N	\N	\N	2024-08-14 15:41:18	\N
117	t1 1	t1@gmail.com	\N	02375070960	0	$2y$12$BPyqyVy5J6sLQd7svfgYruLkPUBcAi2Vm.n2i/eIvdwiDmRtwOQDO	\N	1	1	\N	t1	1	\N	\N	0	\N	2024-08-14 16:08:52	2024-08-14 16:09:07	3	\N	\N	\N	\N	\N	\N	\N	2024-08-14 16:09:07	\N
126	ajesh kumar	ajeshcd@gmail.com	\N	07345274853	0	$2y$12$MruFSqvBPhO7z7MeXdFtG.zoojYtZn5SPGfRVmU9Gg2wFUWWR2wDi	\N	1	1	1	ajesh	kumar	\N	\N	0	\N	2024-08-15 14:53:13	2024-08-17 17:36:46	8	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:36:46	2024-08-15 10:55:53
101	rusvin k	rusvink@gmail.com	\N	01866340147	0	$2y$12$y3M/Tpkw5ku.MUm8UjdShOwUitwcEchDb6cSK9r3uSMV7B4EdTN1a	\N	1	1	1	rusvin	k	\N	\N	0	\N	2024-07-05 09:18:58	2024-08-17 17:36:53	3	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:36:53	2024-07-05 05:32:03
118	345345 34	453@gmail.com	\N	02855338865	0	$2y$12$XVfCaWDyPtG8O41oqP0Ycu7QWPWRsGOKCLjKfBe9tZx7b2DgQ4iFS	\N	1	1	\N	345345	34	\N	\N	0	\N	2024-08-14 16:48:33	2024-08-14 16:58:11	3	\N	\N	\N	\N	\N	\N	\N	2024-08-14 16:58:11	\N
130	345345 4345	345@3.com	971	00000000000	1	$2y$12$qAOEBiX18RafGTJu5.hdi.678JSh.eKA/G.z15dFcw2KBAQjaEn02	\N	\N	1	2	345345	4345	\N	\N	1	\N	2024-08-16 09:49:04	2024-08-17 17:37:36	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:37:36	\N
119	efswe wer	erw@gmail.com	\N	08128867413	0	$2y$12$PuY0do51Y2GfhQiD6fwgbe.bRNrMWajmhfQx/ZTQ.GpYn3/Fi2kJq	\N	1	1	\N	efswe	wer	\N	\N	0	\N	2024-08-14 16:58:52	2024-08-14 17:02:19	3	\N	\N	\N	\N	\N	\N	\N	2024-08-14 17:02:19	\N
120	asdfa try	asasd@gmail.com	971	56456456	1	$2y$12$JBiPdGCHfWwjrZwL9cnnn..AKN8KQFVXU5uaRfJzZdY32BakNk5Tu	\N	\N	1	2	asdfa	try	66bcae4672def_1723641414.png	\N	1	\N	2024-08-14 17:16:55	2024-08-14 17:18:50	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-14 17:18:50	\N
115	dhg ds	sd@gmail.com	971	567567657	1	$2y$12$E2GBKtf5GDucGoza.7iCA.hZxt0I2MDOcBflEqQGjnm5lQi5fotni	\N	\N	1	3	dhg	ds	66bc96e375d7c_1723635427.jpg	\N	1	\N	2024-08-14 15:37:08	2024-08-14 17:50:34	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-14 17:50:34	\N
121	Testing aa	washfajsf@gmail.com	971	33534534	1	$2y$12$/JUiCT8PoQ5YP22X.GewoObmNsqJWBPS1Wf08MK2OyjmgPgeD1Yiy	\N	\N	1	3	Testing	aa	66bcc253d337a_1723646547.jpg	\N	1	\N	2024-08-14 18:42:28	2024-08-14 18:42:36	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-14 18:42:36	\N
122	fdx vsd	sdx@gmail.com	971	456456	1	$2y$12$B.n0lz6WUoIbYvrbMdFm7.XMOpGlbhGLliebc6mlnD1zCr8ukK6xq	\N	\N	1	3	fdx	vsd	66bcc60079d37_1723647488.jpg	\N	1	\N	2024-08-14 18:58:09	2024-08-14 19:03:04	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-14 19:03:04	\N
125	new 1	new@n.com	971	56456464	1	$2y$12$bAP29xFFmS3sSHJXEQsIJu41J9hshCAfE3zREs.BbcqkW/zQKFZQW	\N	\N	1	2	new	1	\N	\N	1	\N	2024-08-15 08:54:13	2024-08-17 17:37:44	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:37:44	\N
123	jasmin s	jas@gmail.com	971	567567567	1	$2y$12$c8hEyV.8dpFNLyWQfJQwbuaIBhgubCcCk/vM5hcZlNUDrLqXQ2UrS	\N	\N	1	3	jasmin	s	66bd83318dbec_1723695921.jpg	\N	1	\N	2024-08-15 08:25:21	2024-08-17 17:38:40	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:38:40	\N
82	nemai twenty	u20@mailinator.com	971	963852741236	1	$2y$12$QlPut/Gxdk5PuTvASONYOenZhwXtUiSuNmIDWj0GU9L31Dc6ctTZ.	\N	\N	1	2	nemai	twenty	666c2e9be2fba_1718365851.jpg	\N	1	\N	2024-06-14 15:44:06	2024-08-17 18:11:22	\N	android	fK5riro2Q3SUagYst49ZnG:APA91bHX0pZx8JTjmezLPu--5FPJrSfFKeAIYWN-bzC3AMbbeuURm_OHYk4Oe4gj3f8kXol6vFEVpzTzRhPzN_0OUm6Qr_I76AjJ4DWcEV0KfLAb7FwBloobw4y2-XG6DF3ud50P8NbH	0fa76acc736e0cd2	\N	\N	\N	\N	2024-08-17 18:11:22	\N
129	324234 342343423424	324@3.com	\N	07984749907	0	$2y$12$wZeGBDpcspBOyHtlaLPd.Ob72BFvBt4mzxkZekmRSvEjOp.souuIq	\N	1	1	\N	324234	342343423424	\N	\N	0	\N	2024-08-16 09:48:01	2024-08-17 17:36:05	3	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:36:05	\N
35	nn biswas6	u9@mailinator.com	91	9332142363	1	$2y$12$Rn.mcNsdEJZWGnqdTH9CLu9qautqSO7yovYvTucOc6o1gE3L2CIKS	\N	\N	1	2	nn	biswas6	666885aa7ddde_1718125994.jpg	\N	1	\N	2024-06-11 10:26:00	2024-08-17 18:11:22	\N	ios	\N	A87BAC40-AD61-4F66-ACA0-E5E52A8E788D	\N	\N	\N	\N	2024-08-17 18:11:22	\N
86	anil nav’s	am@am.com	971	453434846	1	$2y$12$RFoUZe59im7b7ZEMukVyiOq28.xvvmU.g812AHnKSWpXoP3vKMe/q	\N	\N	1	2	anil	nav’s	666d762e056ec_1718449710.jpg	\N	1	\N	2024-06-15 15:07:33	2024-08-17 18:11:22	\N	ios	coViS0h8YUEcrrylIEpgWX:APA91bFABMotdKgGBztfK5tXRx_yMm7ZpO2buqdRuENVMYE6Xcw1_slg6i25z-Td89s_WD9rSg2cQS2E5lBBMZYOD1IQBhCLhmud7dlYO5UzFnKLRxhvhHWPiOkOpAijFPFt3wiHSJjZ	C6698272-3E3A-4781-933B-8F7C5F2BCD72	\N	\N	\N	\N	2024-08-17 18:11:22	\N
132	Asadds As	ass@gmail.com	971	53545345	1	$2y$12$b9ZV0Zz6vz.f0D/vn5tl5.MrJ.rUY68PZtLbW/dW6fSZLETZHr.aW	\N	\N	1	3	Asadds	As	66c2c6527bf39_1724040786.jpg	\N	1	\N	2024-08-19 08:11:59	2024-08-19 08:13:15	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-19 08:13:15	\N
124	silpa m	sil@s.com	\N	02443020531	0	$2y$12$ls2Eo/WXJ61/Bovym5gkuehQpThIUdZqXwaekAHzUGuJUWgiUyrLy	\N	1	1	1	silpa	m	\N	\N	0	\N	2024-08-15 08:47:16	2024-08-17 17:36:19	3	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:36:19	2024-08-16 05:23:51
110	sdfsdf asdf	asd@gamil.com	971	6756756765	1	$2y$12$MM.lzAtJSHVJwK3SpX/pg.NR77PIyksEvSKiUcaNoBImOuzs7n4Li	\N	\N	1	2	sdfsdf	asdf	\N	\N	1	\N	2024-08-14 13:24:41	2024-08-17 18:11:23	\N	\N	\N	\N	\N	\N	\N	\N	2024-08-17 18:11:23	\N
128	check c	check@c.com	\N	03394391897	0	$2y$12$xz2d.vLd5LgkfHsfgqYYW.XZNVf6ersZ/5tSwb.7kSIbX1OKAPRga	\N	1	1	1	check	c	\N	\N	0	\N	2024-08-15 16:07:51	2024-08-17 17:36:26	2	\N	\N	\N	\N	\N	\N	\N	2024-08-17 17:36:26	2024-08-16 04:20:28
\.


--
-- Data for Name: users_role; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users_role (id, role_name, created_at, updated_at) FROM stdin;
1	admin	2024-05-03 08:46:02	2024-05-03 08:46:02
2	users	2024-05-03 08:46:02	2024-05-03 08:46:02
3	vendors	2024-05-03 08:46:02	2024-05-03 08:46:02
\.


--
-- Data for Name: vendor_booking_dates; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vendor_booking_dates (id, booking_id, date, start_time, end_time, resource_id) FROM stdin;
\.


--
-- Data for Name: vendor_booking_media; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vendor_booking_media (id, filename, vendor_booking_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: vendor_bookings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vendor_bookings (id, user_id, title, reference_number, total, advance, order_id, created_at, updated_at, customer_id, status, total_paid, tax, discount, is_rescheduled, hourly_rate, total_with_tax, total_without_tax, total_hours, last_payment_method, temp_reschedule_data, before_reschedule_dates, total_rschdl_paid, disraption, artist_commission, neworer_commission, gateway, cancel_remarks, is_refund_made, refund_file, duration) FROM stdin;
\.


--
-- Data for Name: vendor_portfolios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vendor_portfolios (id, user_id, title, description, filename, mime, type, sort_order, created_at, updated_at) FROM stdin;
1	2	Testing 1	Description testing	6629a8240f0d7_1714006052.jpg	image/jpeg	image	0	\N	\N
5	7	Testing	Testing	66380866bad18_1714948198.jpg	image/jpeg	image	0	\N	\N
4	2	Video 2	testing 2	663094587d440_1714459736.mp4	video/mp4	video	1	\N	2024-05-06 03:01:59
3	2	Video	Testing	66309434b19ad_1714459700.mp4	video/mp4	video	2	\N	2024-05-06 03:01:59
15	10	Music	The art of the live is musics	6669688c52800_1718184076.jpg	image/jpeg	image	0	\N	\N
16	10	Music	The art of the live is musics	6669688c73d07_1718184076.jpg	image/jpeg	image	1	\N	\N
17	10	Music	The art of the live is musics	6669688c7ba11_1718184076.jpg	image/jpeg	image	2	\N	\N
18	10	Music	The art of the live is musics	6669688c82531_1718184076.jpg	image/jpeg	image	3	\N	\N
19	10	Music	The art of the live is musics	6669688c891ca_1718184076.jpg	image/jpeg	image	4	\N	\N
20	10	Music	The art of the live is musics	6669688c978b1_1718184076.jpg	image/jpeg	image	5	\N	\N
21	10	Music	The art of the live is musics	6669688ca04f8_1718184076.jpg	image/jpeg	image	6	\N	\N
22	10	Music	The art of the live is musics	6669688ca68c9_1718184076.jpg	image/jpeg	image	7	\N	\N
23	10	Music	The art of the live is musics	6669688caca8e_1718184076.jpg	image/jpeg	image	8	\N	\N
24	10	Music	The art of the live is musics	6669688cba56f_1718184076.jpg	image/jpeg	image	9	\N	\N
25	10	Music	The art of the live is musics	6669688cc1d0e_1718184076.jpg	image/jpeg	image	10	\N	\N
26	10	Music	The art of the live is musics	6669688cc8672_1718184076.jpg	image/jpeg	image	11	\N	\N
27	10	Music	The art of the live is musics	6669688cdc338_1718184076.jpg	image/jpeg	image	12	\N	\N
28	10	Music	The art of the live is musics	6669688ce3ae1_1718184076.jpg	image/jpeg	image	13	\N	\N
29	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a05f0b4c_1718184453.jpg	image/jpeg	image	0	\N	\N
30	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a065967e_1718184454.jpg	image/jpeg	image	1	\N	\N
31	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a0672b4c_1718184454.jpg	image/jpeg	image	2	\N	\N
32	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a067d635_1718184454.jpg	image/jpeg	image	3	\N	\N
33	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a0687523_1718184454.jpg	image/jpeg	image	4	\N	\N
34	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a068ce65_1718184454.jpg	image/jpeg	image	5	\N	\N
35	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a0693237_1718184454.jpg	image/jpeg	image	6	\N	\N
36	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a06a0b07_1718184454.jpg	image/jpeg	image	7	\N	\N
37	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a06b31f7_1718184454.jpg	image/jpeg	image	8	\N	\N
38	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a06ba819_1718184454.jpg	image/jpeg	image	9	\N	\N
39	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a06c3707_1718184454.jpg	image/jpeg	image	10	\N	\N
40	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a06ca2e9_1718184454.jpg	image/jpeg	image	11	\N	\N
41	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66696a06d007d_1718184454.jpg	image/jpeg	image	12	\N	\N
42	10	Music	The art of the live is musics	66697345a0b18_1718186821.mp4	video/mp4	video	14	\N	\N
43	10	Music	The art of the live is musics	66697345d3b99_1718186821.mp4	video/mp4	video	15	\N	\N
44	10	Music	The art of the live is musics	66697345e6f70_1718186821.mp4	video/mp4	video	16	\N	\N
45	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	666973756370b_1718186869.mp4	video/mp4	video	13	\N	\N
46	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66697375afe92_1718186869.mp4	video/mp4	video	14	\N	\N
47	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66697375c828f_1718186869.mp4	video/mp4	video	15	\N	\N
48	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66697375dab23_1718186869.mp4	video/mp4	video	16	\N	\N
49	27	Musics	Others who use this device won’t see your activity, so you can browse more privately. This won't change how data is collected by websites that you visit and the services that they use, including Google.	66697375ef1be_1718186869.mp4	video/mp4	video	17	\N	\N
50	104	sddg	zsdfsg	66b396ac6e534_1723045548.jpg	image/jpeg	image	0	\N	\N
\.


--
-- Data for Name: vendor_ratings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vendor_ratings (id, user_id, vendor_id, rating, review, created_at, updated_at, booking_id) FROM stdin;
\.


--
-- Data for Name: vendor_user_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vendor_user_details (id, user_id, username, date_of_birth, lattitude, longitude, location_name, about, instagram, twitter, facebook, tiktok, gender, c_policy, r_policy, reference_number, hourly_rate, advance_percent, availability_from, category_id, type, total_rating, thread, availability_to, deposit_amount, categories) FROM stdin;
1	131	wahabfun	1993-08-26	25.204819	55.270931	\N	<p>te</p>	\N	\N	\N	\N	male	\N	\N	967136	80.00	0	2024-08-21	2	resident	0.00	\N	2024-08-27	20.00	2
2	132	asd	2010-08-19	25.1000998	55.2380812	Dubai Hills Mall Storm Coaster - Dubai - United Arab Emirates	<p>ws</p>	\N	\N	\N	\N	male	\N	\N	276192	13342.00	0	2024-08-27	3	resident	0.00	\N	2024-08-28	22.00	3
\.


--
-- Name: app_banners_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.app_banners_id_seq', 7, true);


--
-- Name: articles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.articles_id_seq', 13, true);


--
-- Name: booking_orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.booking_orders_id_seq', 1, false);


--
-- Name: booking_resources_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.booking_resources_id_seq', 13, true);


--
-- Name: category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.category_id_seq', 3, true);


--
-- Name: contact_us_entries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contact_us_entries_id_seq', 7, true);


--
-- Name: country_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.country_id_seq', 250, true);


--
-- Name: customer_ratings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.customer_ratings_id_seq', 1, true);


--
-- Name: customer_user_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.customer_user_details_id_seq', 83, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: favourites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.favourites_id_seq', 35, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 39, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 516, true);


--
-- Name: role_permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.role_permissions_id_seq', 152, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 8, true);


--
-- Name: settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.settings_id_seq', 14, true);


--
-- Name: temp_transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.temp_transactions_id_seq', 156, true);


--
-- Name: temp_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.temp_users_id_seq', 92, true);


--
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transactions_id_seq', 326, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 132, true);


--
-- Name: users_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_role_id_seq', 3, true);


--
-- Name: vendor_booking_dates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vendor_booking_dates_id_seq', 1, false);


--
-- Name: vendor_booking_media_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vendor_booking_media_id_seq', 1, false);


--
-- Name: vendor_bookings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vendor_bookings_id_seq', 1, false);


--
-- Name: vendor_portfolios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vendor_portfolios_id_seq', 50, true);


--
-- Name: vendor_ratings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vendor_ratings_id_seq', 1, false);


--
-- Name: vendor_user_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vendor_user_details_id_seq', 2, true);


--
-- Name: app_banners app_banners_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.app_banners
    ADD CONSTRAINT app_banners_pkey PRIMARY KEY (id);


--
-- Name: articles articles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.articles
    ADD CONSTRAINT articles_pkey PRIMARY KEY (id);


--
-- Name: booking_orders booking_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.booking_orders
    ADD CONSTRAINT booking_orders_pkey PRIMARY KEY (id);


--
-- Name: booking_resources booking_resources_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.booking_resources
    ADD CONSTRAINT booking_resources_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: category category_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category
    ADD CONSTRAINT category_pkey PRIMARY KEY (id);


--
-- Name: contact_us_entries contact_us_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact_us_entries
    ADD CONSTRAINT contact_us_entries_pkey PRIMARY KEY (id);


--
-- Name: country country_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.country
    ADD CONSTRAINT country_pkey PRIMARY KEY (id);


--
-- Name: customer_ratings customer_ratings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.customer_ratings
    ADD CONSTRAINT customer_ratings_pkey PRIMARY KEY (id);


--
-- Name: customer_user_details customer_user_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.customer_user_details
    ADD CONSTRAINT customer_user_details_pkey PRIMARY KEY (id);


--
-- Name: customer_user_details customer_user_details_wallet_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.customer_user_details
    ADD CONSTRAINT customer_user_details_wallet_id_unique UNIQUE (wallet_id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: favourites favourites_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favourites
    ADD CONSTRAINT favourites_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: role_permissions role_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permissions
    ADD CONSTRAINT role_permissions_pkey PRIMARY KEY (id);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: settings settings_meta_key_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_meta_key_unique UNIQUE (meta_key);


--
-- Name: settings settings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- Name: temp_transactions temp_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.temp_transactions
    ADD CONSTRAINT temp_transactions_pkey PRIMARY KEY (id);


--
-- Name: temp_users temp_users_phone_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.temp_users
    ADD CONSTRAINT temp_users_phone_unique UNIQUE (phone);


--
-- Name: temp_users temp_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.temp_users
    ADD CONSTRAINT temp_users_pkey PRIMARY KEY (id);


--
-- Name: transactions transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_pkey PRIMARY KEY (id);


--
-- Name: users users_phone_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_phone_unique UNIQUE (phone);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_role users_role_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_role
    ADD CONSTRAINT users_role_pkey PRIMARY KEY (id);


--
-- Name: vendor_booking_dates vendor_booking_dates_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_booking_dates
    ADD CONSTRAINT vendor_booking_dates_pkey PRIMARY KEY (id);


--
-- Name: vendor_booking_media vendor_booking_media_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_booking_media
    ADD CONSTRAINT vendor_booking_media_pkey PRIMARY KEY (id);


--
-- Name: vendor_bookings vendor_bookings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_bookings
    ADD CONSTRAINT vendor_bookings_pkey PRIMARY KEY (id);


--
-- Name: vendor_portfolios vendor_portfolios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_portfolios
    ADD CONSTRAINT vendor_portfolios_pkey PRIMARY KEY (id);


--
-- Name: vendor_ratings vendor_ratings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_ratings
    ADD CONSTRAINT vendor_ratings_pkey PRIMARY KEY (id);


--
-- Name: vendor_user_details vendor_user_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_user_details
    ADD CONSTRAINT vendor_user_details_pkey PRIMARY KEY (id);


--
-- Name: booking_orders_booking_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX booking_orders_booking_id_index ON public.booking_orders USING btree (booking_id);


--
-- Name: booking_orders_customer_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX booking_orders_customer_id_index ON public.booking_orders USING btree (customer_id);


--
-- Name: booking_orders_order_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX booking_orders_order_id_index ON public.booking_orders USING btree (order_id);


--
-- Name: booking_orders_reference_number_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX booking_orders_reference_number_index ON public.booking_orders USING btree (reference_number);


--
-- Name: booking_orders_status_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX booking_orders_status_index ON public.booking_orders USING btree (status);


--
-- Name: booking_orders_vendor_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX booking_orders_vendor_id_index ON public.booking_orders USING btree (vendor_id);


--
-- Name: customer_ratings_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX customer_ratings_user_id_index ON public.customer_ratings USING btree (user_id);


--
-- Name: customer_ratings_vendor_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX customer_ratings_vendor_id_index ON public.customer_ratings USING btree (vendor_id);


--
-- Name: customer_user_details_total_rating_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX customer_user_details_total_rating_index ON public.customer_user_details USING btree (total_rating);


--
-- Name: customer_user_details_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX customer_user_details_user_id_index ON public.customer_user_details USING btree (user_id);


--
-- Name: customer_user_details_wallet_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX customer_user_details_wallet_id_index ON public.customer_user_details USING btree (wallet_id);


--
-- Name: favourites_customer_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX favourites_customer_id_index ON public.favourites USING btree (customer_id);


--
-- Name: favourites_vendor_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX favourites_vendor_id_index ON public.favourites USING btree (vendor_id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: temp_transactions_p_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX temp_transactions_p_id_index ON public.temp_transactions USING btree (p_id);


--
-- Name: temp_transactions_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX temp_transactions_type_index ON public.temp_transactions USING btree (type);


--
-- Name: temp_users_access_token_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX temp_users_access_token_index ON public.temp_users USING btree (access_token);


--
-- Name: temp_users_dial_code_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX temp_users_dial_code_index ON public.temp_users USING btree (dial_code);


--
-- Name: temp_users_email_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX temp_users_email_index ON public.temp_users USING btree (email);


--
-- Name: temp_users_phone_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX temp_users_phone_index ON public.temp_users USING btree (phone);


--
-- Name: transactions_customer_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_customer_id_index ON public.transactions USING btree (customer_id);


--
-- Name: transactions_order_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_order_id_index ON public.transactions USING btree (order_id);


--
-- Name: transactions_other_customer_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_other_customer_id_index ON public.transactions USING btree (other_customer_id);


--
-- Name: transactions_p_trans_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_p_trans_id_index ON public.transactions USING btree (p_trans_id);


--
-- Name: transactions_payment_method_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_payment_method_index ON public.transactions USING btree (payment_method);


--
-- Name: transactions_status_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_status_index ON public.transactions USING btree (status);


--
-- Name: transactions_transaction_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_transaction_id_index ON public.transactions USING btree (transaction_id);


--
-- Name: transactions_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_type_index ON public.transactions USING btree (type);


--
-- Name: transactions_vendor_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_vendor_id_index ON public.transactions USING btree (vendor_id);


--
-- Name: users_device_cart_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_device_cart_id_index ON public.users USING btree (device_cart_id);


--
-- Name: users_fcm_token_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_fcm_token_index ON public.users USING btree (fcm_token);


--
-- Name: users_forget_pass_token_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_forget_pass_token_index ON public.users USING btree (password_reset_code);


--
-- Name: users_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_id_index ON public.users USING btree (id);


--
-- Name: users_role_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_role_id_index ON public.users USING btree (role_id);


--
-- Name: vendor_booking_dates_booking_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_booking_dates_booking_id_index ON public.vendor_booking_dates USING btree (booking_id);


--
-- Name: vendor_booking_dates_date_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_booking_dates_date_index ON public.vendor_booking_dates USING btree (date);


--
-- Name: vendor_booking_dates_start_time_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_booking_dates_start_time_index ON public.vendor_booking_dates USING btree (start_time);


--
-- Name: vendor_booking_media_vendor_booking_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_booking_media_vendor_booking_id_index ON public.vendor_booking_media USING btree (vendor_booking_id);


--
-- Name: vendor_bookings_customer_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_bookings_customer_id_index ON public.vendor_bookings USING btree (customer_id);


--
-- Name: vendor_bookings_order_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_bookings_order_id_index ON public.vendor_bookings USING btree (order_id);


--
-- Name: vendor_bookings_reference_number_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_bookings_reference_number_index ON public.vendor_bookings USING btree (reference_number);


--
-- Name: vendor_bookings_status_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_bookings_status_index ON public.vendor_bookings USING btree (status);


--
-- Name: vendor_bookings_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_bookings_user_id_index ON public.vendor_bookings USING btree (user_id);


--
-- Name: vendor_portfolios_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_portfolios_user_id_index ON public.vendor_portfolios USING btree (user_id);


--
-- Name: vendor_ratings_booking_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_ratings_booking_id_index ON public.vendor_ratings USING btree (booking_id);


--
-- Name: vendor_ratings_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_ratings_user_id_index ON public.vendor_ratings USING btree (user_id);


--
-- Name: vendor_ratings_vendor_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_ratings_vendor_id_index ON public.vendor_ratings USING btree (vendor_id);


--
-- Name: vendor_user_details_availability_from_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_user_details_availability_from_index ON public.vendor_user_details USING btree (availability_from);


--
-- Name: vendor_user_details_availability_to_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_user_details_availability_to_index ON public.vendor_user_details USING btree (availability_to);


--
-- Name: vendor_user_details_category_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_user_details_category_id_index ON public.vendor_user_details USING btree (category_id);


--
-- Name: vendor_user_details_reference_number_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_user_details_reference_number_index ON public.vendor_user_details USING btree (reference_number);


--
-- Name: vendor_user_details_total_rating_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_user_details_total_rating_index ON public.vendor_user_details USING btree (total_rating);


--
-- Name: vendor_user_details_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_user_details_type_index ON public.vendor_user_details USING btree (type);


--
-- Name: vendor_user_details_username_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vendor_user_details_username_index ON public.vendor_user_details USING btree (username);


--
-- Name: booking_orders booking_orders_booking_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.booking_orders
    ADD CONSTRAINT booking_orders_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES public.vendor_bookings(id) ON DELETE CASCADE;


--
-- Name: booking_orders booking_orders_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.booking_orders
    ADD CONSTRAINT booking_orders_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: booking_orders booking_orders_vendor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.booking_orders
    ADD CONSTRAINT booking_orders_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: customer_ratings customer_ratings_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.customer_ratings
    ADD CONSTRAINT customer_ratings_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: customer_ratings customer_ratings_vendor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.customer_ratings
    ADD CONSTRAINT customer_ratings_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: customer_user_details customer_user_details_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.customer_user_details
    ADD CONSTRAINT customer_user_details_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: transactions transactions_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: transactions transactions_other_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_other_customer_id_foreign FOREIGN KEY (other_customer_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: transactions transactions_vendor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: vendor_booking_dates vendor_booking_dates_booking_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_booking_dates
    ADD CONSTRAINT vendor_booking_dates_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES public.vendor_bookings(id) ON DELETE CASCADE;


--
-- Name: vendor_booking_media vendor_booking_media_vendor_booking_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_booking_media
    ADD CONSTRAINT vendor_booking_media_vendor_booking_id_foreign FOREIGN KEY (vendor_booking_id) REFERENCES public.vendor_bookings(id) ON DELETE CASCADE;


--
-- Name: vendor_bookings vendor_bookings_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_bookings
    ADD CONSTRAINT vendor_bookings_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: vendor_bookings vendor_bookings_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_bookings
    ADD CONSTRAINT vendor_bookings_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: vendor_portfolios vendor_portfolios_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_portfolios
    ADD CONSTRAINT vendor_portfolios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: vendor_ratings vendor_ratings_booking_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_ratings
    ADD CONSTRAINT vendor_ratings_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES public.vendor_bookings(id) ON DELETE CASCADE;


--
-- Name: vendor_ratings vendor_ratings_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_ratings
    ADD CONSTRAINT vendor_ratings_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: vendor_ratings vendor_ratings_vendor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_ratings
    ADD CONSTRAINT vendor_ratings_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: vendor_user_details vendor_user_details_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_user_details
    ADD CONSTRAINT vendor_user_details_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.category(id) ON DELETE SET NULL;


--
-- Name: vendor_user_details vendor_user_details_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vendor_user_details
    ADD CONSTRAINT vendor_user_details_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

