/**
 * Shapes of the data the backend sends to pages. Each type mirrors one
 * class in app/Http/Resources (or a shared prop in HandleInertiaRequests).
 * Text is already resolved to the current language.
 */

export interface Image {
    id: number;
    url: string;
    thumb: string;
    medium: string;
    large: string;
    alt: string;
    width: number | null;
    height: number | null;
}

export interface Seo {
    title: string;
    description: string | null;
    canonical: string;
    robots: string;
    image: string;
    type: string;
    siteName: string;
    locale: Locale;
    alternates: Partial<Record<Locale, string>>;
    breadcrumbs: { name: string; url: string }[];
    jsonLd: Record<string, unknown>;
}

export type Locale = 'en' | 'bn';
export type WhatsAppTemplate = 'default' | 'service' | 'solution' | 'project' | 'plan' | 'estimator';

export interface Site {
    name: string;
    tagline: string | null;
    slogan: string | null;
    description: string | null;
    email: string;
    phone: string;
    phoneDisplay: string;
    address: string | null;
    hours: string | null;
    social: Partial<Record<'facebook' | 'instagram' | 'linkedin' | 'youtube' | 'google_business', string>>;
    logo: { color: string; logo: string; logo_white: string; icon: string; icon_white: string };
    tracking: { gtm: string | null; pixel: string | null };
    cta: { title: string | null; lead: string | null };
    whatsapp: { number: string; url: string; templates: Record<WhatsAppTemplate, string> };
}

export interface NavLink {
    title: string | null;
    url: string;
}

/** Props present on every page. */
export interface SharedProps {
    locale: Locale;
    locales: Locale[];
    seo: Seo;
    site: Site;
    navigation: { services: NavLink[]; solutions: NavLink[] };
    flash: { success: string | null; error: string | null };
    auth: { id: number; name: string; email: string; roles: string[]; permissions: string[]; twoFactor: boolean } | null;
    adminMenu: { label: string; items: { label: string; href: string; icon: string }[] }[];
    notifications: { unread: number; items: AdminNotification[] } | null;
    realtime: { enabled?: boolean };
}

export interface Industry {
    id: number;
    slug: string;
    name: string;
    description: string | null;
    icon: string | null;
    url: string;
    projects_count?: number;
}

export interface Client {
    id: number;
    slug: string;
    name: string;
    website_url: string | null;
    location: string | null;
    logo: Image | null;
}

export interface Stat {
    value: string;
    suffix: string | null;
    label: string;
}

export interface Faq {
    id: number;
    group: string | null;
    question: string;
    answer: string; // sanitised HTML
}

export interface PricingPlan {
    id: number;
    name: string;
    description: string | null;
    price: number | null; // null means "get a quote"
    currency: string;
    period: string | null;
    features: string[];
    is_popular: boolean;
    whatsapp_url: string;
}

export interface Testimonial {
    id: number;
    author_name: string;
    author_role: string | null;
    quote: string;
    rating: number | null;
    video_url: string | null;
    avatar: Image | null;
    client?: string | null;
    project_url?: string | null;
}

export interface TeamMember {
    name: string;
    role: string | null;
    bio: string | null;
    socials: { label: string; url: string }[];
    photo: Image | null;
}

export interface TitledRow {
    title: string | null;
    description: string | null;
}

export interface Service {
    id: number;
    slug: string;
    url: string;
    title: string;
    tagline: string | null;
    excerpt: string | null;
    icon: string | null;
    is_featured: boolean;
    cover: Image | null;
    children?: Service[];
    parent?: { title: string; url: string } | null;
    // Detail pages only:
    body?: string | null;
    features?: TitledRow[];
    process_steps?: TitledRow[];
}

export interface SolutionFeature {
    slug: string;
    title: string;
    description: string | null;
    icon: string | null;
    image?: Image | null;
}

export interface SolutionFeatureGroup {
    key: string;
    name: string;
    description: string | null;
    features: SolutionFeature[];
}

export interface Solution {
    id: number;
    slug: string;
    url: string;
    name: string;
    tagline: string | null;
    summary: string | null;
    icon: string | null;
    is_flagship: boolean;
    demo_url: string | null;
    external_url: string | null;
    cover: Image | null;
    projects_count?: number;
    feature_groups?: SolutionFeatureGroup[];
    body?: string | null;
}

export interface Project {
    id: number;
    slug: string;
    url: string;
    title: string;
    tagline: string | null;
    summary: string | null;
    type: 'live' | 'demo' | 'product';
    is_demo: boolean;
    is_featured: boolean;
    live_url: string | null;
    cover: Image | null;
    client?: { name: string; logo: Image | null } | null;
    industry?: { name: string; slug: string; url: string } | null;
    solution?: { name: string; slug: string; url: string } | null;
    service_slugs?: string[];
    // Case study page only:
    challenge?: string | null;
    approach?: string | null;
    outcome?: string | null;
    launched_at?: string | null;
    metrics?: { value: string | null; label: string | null }[];
    mobile?: Image | null;
    gallery?: Image[];
    services?: { title: string; url: string }[];
    technologies?: { slug: string; name: string; category: string | null }[];
    features?: { slug: string; title: string }[];
    whatsapp_url?: string;
}

export interface Post {
    id: number;
    slug: string;
    url: string;
    title: string;
    excerpt: string | null;
    reading_minutes: number;
    published_at: string | null;
    updated_at: string | null;
    cover: Image | null;
    author?: string | null;
    category?: { name: string; url: string } | null;
    tags?: { name: string; url: string }[];
    body?: string | null;
}

/** Body of POST /api/leads. `website` and `started_at` are spam traps. */
export interface LeadInput {
    name: string;
    phone: string;
    email?: string;
    company?: string;
    message?: string;
    budget_range?: string;
    source?: 'contact_form' | 'estimator' | 'demo_request';
    service_id?: number;
    solution_id?: number;
    project_id?: number;
    meta?: Record<string, string>;
    page_url?: string;
    locale?: Locale;
    website?: string;
    started_at?: number;
}

/** Body of POST /api/events (sent with navigator.sendBeacon). */
export interface ConversionEventInput {
    type: 'whatsapp_click' | 'email_click' | 'demo_click' | 'form_start' | 'form_submit' | 'estimator_step';
    placement?: string;
    page_url?: string;
    locale?: Locale;
}

/** One form field of an admin module (App\Admin\Field::toArray). */
export interface AdminField {
    name: string;
    type:
        | 'text'
        | 'textarea'
        | 'richtext'
        | 'slug'
        | 'number'
        | 'toggle'
        | 'url'
        | 'email'
        | 'password'
        | 'date'
        | 'datetime'
        | 'select'
        | 'multiselect'
        | 'image'
        | 'images'
        | 'repeater';
    label: string;
    translatable: boolean;
    required: boolean;
    max: number | null;
    help: string | null;
    default: unknown;
    group: 'main' | 'side';
    options: { value: string | number; label: string }[] | null;
    children: AdminField[];
}

export interface AdminNotification {
    id: string;
    data: { lead_id?: number; name?: string | null; phone?: string | null; source?: string };
    read: boolean;
    created_at: string | null;
}

/** A section heading edited in Settings > Home page. */
export interface SectionCopy {
    title: string | null;
    lead: string | null;
}

export interface PageIntro {
    heading: string | null;
    lead: string | null;
}

export interface TextRow {
    icon?: string | null;
    title: string;
    description: string | null;
}
