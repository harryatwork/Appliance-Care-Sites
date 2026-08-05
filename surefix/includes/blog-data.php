<?php
/**
 * Placeholder blog content for Phase 1 (design only). Structured to mirror
 * what the future `blog_posts` DB table will look like (Phase 5), so
 * swapping this array for a real query later is a small, contained change —
 * blog.php and blog-post.php shouldn't need to change their markup at all.
 */

$blogPosts = [
    [
        'slug' => 'signs-your-ac-needs-servicing',
        'category' => 'AC Care',
        'date' => 'March 12, 2026',
        'title' => '5 Signs Your AC Needs Servicing Before Summer',
        'icon' => 'fa-wind',
        'excerpt' => 'Weak cooling, strange noises, and bad odors are early warning signs — here\'s what to watch for before peak summer hits.',
        'body' => "Bengaluru summers put real strain on air conditioners that haven't been serviced since last year. Here are five signs it's time to book a checkup before the heat peaks.\n\nWeak or warm airflow, unusual noises during startup, a musty smell when the AC kicks in, visible water leakage near the indoor unit, and higher-than-usual electricity bills are all early indicators of a unit that needs attention. Catching these early usually means a simple service call instead of an emergency repair mid-summer.",
    ],
    [
        'slug' => 'extend-refrigerator-lifespan',
        'category' => 'Refrigerator',
        'date' => 'March 4, 2026',
        'title' => "How to Extend Your Refrigerator's Lifespan",
        'icon' => 'fa-snowflake',
        'excerpt' => 'A few simple habits can add years to your refrigerator\'s working life and keep your energy bills down.',
        'body' => "A well-maintained refrigerator can easily run for 12-15 years. Simple habits make a real difference: keep the coils dust-free, don't overpack shelves (airflow matters), check door seals for gaps, and avoid setting the thermostat colder than necessary.\n\nIf you notice your fridge running louder than usual or struggling to stay cold, don't wait — small issues caught early are far cheaper to fix than a full compressor failure later.",
    ],
    [
        'slug' => 'washing-machine-maintenance-monsoon',
        'category' => 'Washing Machine',
        'date' => 'February 21, 2026',
        'title' => 'Washing Machine Maintenance Tips for Monsoon Season',
        'icon' => 'fa-shirt',
        'excerpt' => 'Humidity and power fluctuations during monsoon can be tough on washing machines — here\'s how to protect yours.',
        'body' => "Monsoon season in Bengaluru brings humidity and power fluctuations that can affect your washing machine's electronics and drainage system. Keep the machine covered when not in use, ensure the drainage hose has a clear, downward path, and consider a stabilizer if your area sees frequent voltage fluctuations.\n\nIf you notice the drum taking longer to drain or unusual odors during monsoon months, it's often a sign of a partially blocked drain — a quick and inexpensive fix if caught early.",
    ],
];

/**
 * Look up a single post by slug. Returns null if not found — callers should
 * handle that (blog-post.php shows a friendly "not found" state).
 */
function findBlogPostBySlug(array $posts, string $slug): ?array
{
    foreach ($posts as $post) {
        if ($post['slug'] === $slug) {
            return $post;
        }
    }
    return null;
}
