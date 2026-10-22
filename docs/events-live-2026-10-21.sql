-- ---------------------------------------------------------------------------
-- The events on www.pomdr.org on 2026-10-21, as rows for the Hub's `events`
-- table, so the new site's Events page carries what the old site carries.
-- Read from adoptionevents.php and news.html that day; every title, time,
-- place and description is the live site's own text. Six events: three
-- adoption events, three special events. Items on What's Happening that are
-- not events (the Silver Hearts Fund, the bourbon affiliate, the book,
-- Wooftrax) are announcements and are not included here.
--
-- Safe to run more than once: each INSERT is skipped when the slug exists.
--
--     mysql <production> pomdr < events-live-2026-10-21.sql
--
-- Dates are 2026 (news.html says so; the weekdays agree). Times are Pacific,
-- which is how `events.starts_at` is read everywhere else.
--
-- One fact to check with staff before the 10 November event: news.html gives
-- the Benefit Shop's new address as 229B Grand Ave, while the shop's own page
-- and the new site say 223 Grand Avenue, Suite 1. The row below uses the
-- address the event announcement gives.
-- ---------------------------------------------------------------------------

INSERT INTO events (title, slug, event_type, short_description, description, starts_at, ends_at, is_all_day, location_name, address_line, city, state, external_url, status)
SELECT 'Pet Pals adoption event', 'pet-pals-2026-10-25', 'Adoption Event',
       'POMDR information table and adoptable dogs.',
       'POMDR information table & adoptable dogs.',
       '2026-10-25 11:00:00', '2026-10-25 13:30:00', 0,
       'Pet Pals', '3660 Soquel Drive', 'Soquel', 'CA', NULL, 'published'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE slug = 'pet-pals-2026-10-25');

INSERT INTO events (title, slug, event_type, short_description, description, starts_at, ends_at, is_all_day, location_name, address_line, city, state, external_url, status)
SELECT 'Sugar Farms Halloween Dog Costume Contest', 'sugar-farms-halloween-2026-10-31', 'Adoption Event',
       'Come and meet some of our adoptable dogs.',
       'Come and meet some of our adoptable dogs.',
       '2026-10-31 13:00:00', '2026-10-31 16:00:00', 0,
       'Sugar Farms', '601 Lighthouse Ave', 'Pacific Grove', 'CA', NULL, 'published'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE slug = 'sugar-farms-halloween-2026-10-31');

INSERT INTO events (title, slug, event_type, short_description, description, starts_at, ends_at, is_all_day, location_name, address_line, city, state, external_url, status)
SELECT 'Annieglass adoption event', 'annieglass-2026-10-31', 'Adoption Event',
       'Info table, adoptable dogs and a dog costume contest!',
       'Info Table, Adoptable Dogs and a Dog Costume Contest!',
       '2026-10-31 13:30:00', '2026-10-31 16:00:00', 0,
       'Annieglass', '310 Harvest Dr', 'Watsonville', 'CA', NULL, 'published'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE slug = 'annieglass-2026-10-31');

INSERT INTO events (title, slug, event_type, short_description, description, starts_at, ends_at, is_all_day, location_name, address_line, city, state, external_url, status)
SELECT 'Volunteer Orientation and Foster Overview via Zoom', 'volunteer-orientation-2026-10-28', 'Special Event',
       'All new and current volunteers are invited to attend this orientation. If you are considering volunteering, we would love to have you join as well.',
       'All new and current volunteers are invited to attend this orientation. If you are considering volunteering, we would love to have you join as well. For more information about our volunteer opportunities or to fill out an application, see the Volunteer page.',
       '2026-10-28 17:30:00', '2026-10-28 19:30:00', 0,
       'On Zoom', NULL, NULL, NULL, 'https://secure.lglforms.com/form_engine/s/KYhH3bc04tvSRM20hyK_4g', 'published'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE slug = 'volunteer-orientation-2026-10-28');

INSERT INTO events (title, slug, event_type, short_description, description, starts_at, ends_at, is_all_day, location_name, address_line, city, state, external_url, status)
SELECT 'Benefit Shop Ribbon Cutting at New Location!', 'benefit-shop-ribbon-cutting-2026-11-10', 'Special Event',
       'Join the Pacific Grove Chamber of Commerce and POMDR for a ribbon cutting and mixer celebrating the Benefit Shop''s new location. Ribbon cutting at 4:30 pm. Free and open to the public.',
       'Join the Pacific Grove Chamber of Commerce and POMDR for a ribbon cutting and mixer celebrating the Benefit Shop''s new location. Come see the new space, meet our team and volunteers, and browse the books, home goods, and holiday finds. Ribbon cutting at 4:30 pm. Cost: Free and open to the public. Every purchase at the shop supports POMDR.',
       '2026-11-10 16:00:00', '2026-11-10 18:00:00', 0,
       'Peace of Mind Dog Rescue Benefit Shop', '229B Grand Ave', 'Pacific Grove', 'CA', NULL, 'published'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE slug = 'benefit-shop-ribbon-cutting-2026-11-10');

INSERT INTO events (title, slug, event_type, short_description, description, starts_at, ends_at, is_all_day, location_name, address_line, city, state, external_url, status)
SELECT 'Paws & Presents Holiday Market', 'paws-and-presents-2026-12-06', 'Special Event',
       'Step into the spirit of the season with this festive afternoon of shopping, sipping, and celebrating in support of Peace of Mind Dog Rescue.',
       'Step into the spirit of the season with this festive afternoon of shopping, sipping, and celebrating in support of Peace of Mind Dog Rescue. Enjoy artisan goods, festive food and drinks and a joyful, dog-loving atmosphere. Sponsored by Robin Venuti, Realtor at Coldwell Bank Realty, and Mechanics Bank.',
       '2026-12-06 13:00:00', '2026-12-06 15:30:00', 0,
       'The Club at Pasadera', NULL, 'Monterey', 'CA',
       'https://www.eventbrite.com/e/tpc-monterey-at-pasadera-presents-paws-presents-holiday-market-tickets-1986720022067?aff=od', 'published'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE slug = 'paws-and-presents-2026-12-06');
