<?php

namespace Database\Seeders;

use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogLike;
use App\Models\BlogPost;
use App\Models\BlogSeries;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
        if (!$admin) {
            $admin = User::first();
        }

        $travelers = User::whereHas('roles', fn($q) => $q->where('name', 'traveler'))->get();

        // Create categories
        $categories = $this->createCategories();

        // Create series
        $series = $this->createSeries();

        // Create tags
        $tags = $this->createTags();

        // Create posts
        $posts = $this->createPosts($admin, $categories, $series, $tags);

        // Create comments
        $this->createComments($posts, $travelers);

        // Create likes
        $this->createLikes($posts, $travelers);

        // Update counts
        $this->updateCounts($categories, $series, $tags);
    }

    private function createCategories(): array
    {
        $categoriesData = [
            ['name' => '여행 가이드', 'description' => '국내외 여행지 가이드와 추천 코스', 'sort_order' => 1],
            ['name' => '맛집 탐방', 'description' => '여행지 맛집과 로컬 음식 소개', 'sort_order' => 2],
            ['name' => '여행 팁', 'description' => '알아두면 좋은 여행 팁과 노하우', 'sort_order' => 3],
            ['name' => '숙소 리뷰', 'description' => '호텔, 게스트하우스, 펜션 리뷰', 'sort_order' => 4],
            ['name' => '액티비티', 'description' => '여행지에서 즐길 수 있는 액티비티', 'sort_order' => 5],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[$data['name']] = BlogCategory::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'],
                'sort_order' => $data['sort_order'],
                'is_active' => true,
            ]);
        }

        return $categories;
    }

    private function createSeries(): array
    {
        $seriesData = [
            [
                'title' => '제주도 완전정복',
                'description' => '제주도의 모든 것을 알려드리는 완전정복 시리즈',
                'thumbnail' => 'https://images.unsplash.com/photo-1555992336-fb0d29498b13?w=800',
            ],
            [
                'title' => '서울 로컬 여행',
                'description' => '서울 토박이가 알려주는 진짜 서울 여행',
                'thumbnail' => 'https://images.unsplash.com/photo-1538485399081-7191377e8241?w=800',
            ],
            [
                'title' => '부산 여행 가이드',
                'description' => '바다와 맛집의 도시 부산 여행 가이드',
                'thumbnail' => 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=800',
            ],
        ];

        $series = [];
        foreach ($seriesData as $data) {
            $thumbnailPath = $this->downloadThumbnail($data['thumbnail'], 'series');

            $series[$data['title']] = BlogSeries::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'description' => $data['description'],
                'thumbnail_path' => $thumbnailPath,
                'is_active' => true,
            ]);
        }

        return $series;
    }

    private function createTags(): array
    {
        $tagNames = [
            '제주도', '서울', '부산', '경주', '강릉', '전주',
            '맛집', '카페', '호텔', '게스트하우스', '펜션',
            '자연', '바다', '산', '섬', '해변',
            '가족여행', '커플여행', '혼자여행', '친구여행',
            '사진스팟', '야경', '일출', '일몰',
            '액티비티', '스노클링', '서핑', '트레킹', '캠핑',
            '맛집투어', '카페투어', '시장투어',
            '힐링', '휴양', '문화탐방', '역사탐방',
        ];

        $tags = [];
        foreach ($tagNames as $name) {
            $tags[$name] = BlogTag::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        return $tags;
    }

    private function createPosts(User $admin, array $categories, array $series, array $tags): array
    {
        $postsData = $this->getPostsData();
        $posts = [];

        foreach ($postsData as $index => $data) {
            $category = $categories[$data['category']] ?? null;
            $postSeries = isset($data['series']) ? ($series[$data['series']] ?? null) : null;

            $thumbnailPath = $this->downloadThumbnail($data['thumbnail'], 'posts');

            $post = BlogPost::create([
                'user_id' => $admin->id,
                'category_id' => $category?->id,
                'series_id' => $postSeries?->id,
                'series_order' => $data['series_order'] ?? null,
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . uniqid(),
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'thumbnail_path' => $thumbnailPath,
                'status' => BlogPostStatus::PUBLISHED,
                'published_at' => now()->subDays(rand(1, 60)),
                'view_count' => rand(100, 5000),
                'meta_title' => $data['title'],
                'meta_description' => $data['excerpt'],
            ]);

            // Attach tags
            $postTags = collect($data['tags'])->map(fn($t) => $tags[$t]->id ?? null)->filter();
            $post->tags()->attach($postTags);

            $posts[] = $post;
        }

        return $posts;
    }

    private function createComments(array $posts, $travelers): void
    {
        $commentTexts = [
            '정말 유용한 정보 감사합니다! 다음 여행 때 참고할게요.',
            '사진이 너무 예쁘네요. 저도 꼭 가보고 싶어요!',
            '와 여기 진짜 좋아보여요. 자세한 리뷰 감사합니다.',
            '친구들이랑 다녀왔는데 정말 좋았어요. 추천합니다!',
            '혼자 여행하기에도 좋은 곳인가요?',
            '교통편은 어떻게 되나요? 대중교통으로도 갈 수 있나요?',
            '예약은 미리 해야 하나요?',
            '가격 정보도 있으면 좋겠어요.',
            '저도 다녀왔는데 완전 공감해요!',
            '이런 글 정말 도움이 많이 돼요. 감사합니다!',
        ];

        $replyTexts = [
            '질문 감사합니다! 본문에 추가로 답변 드릴게요.',
            '네, 가능합니다! 자세한 내용은 본문 참고해주세요.',
            '공감해주셔서 감사합니다!',
            '도움이 되셨다니 기쁘네요!',
        ];

        if ($travelers->isEmpty()) {
            return;
        }

        foreach ($posts as $post) {
            $commentCount = rand(2, 6);

            for ($i = 0; $i < $commentCount; $i++) {
                $user = $travelers->random();

                $comment = BlogComment::create([
                    'blog_post_id' => $post->id,
                    'user_id' => $user->id,
                    'content' => $commentTexts[array_rand($commentTexts)],
                    'is_approved' => true,
                    'is_visible' => true,
                ]);

                // Sometimes add a reply
                if (rand(0, 1)) {
                    BlogComment::create([
                        'blog_post_id' => $post->id,
                        'user_id' => $post->user_id,
                        'parent_id' => $comment->id,
                        'content' => $replyTexts[array_rand($replyTexts)],
                        'is_approved' => true,
                        'is_visible' => true,
                    ]);
                }
            }

            // Update comment count
            $post->update([
                'comment_count' => $post->comments()->count(),
            ]);
        }
    }

    private function createLikes(array $posts, $travelers): void
    {
        if ($travelers->isEmpty()) {
            return;
        }

        foreach ($posts as $post) {
            $likeCount = rand(5, 30);
            $likers = $travelers->random(min($likeCount, $travelers->count()));

            foreach ($likers as $user) {
                BlogLike::create([
                    'blog_post_id' => $post->id,
                    'user_id' => $user->id,
                ]);
            }

            $post->update([
                'like_count' => $post->likes()->count(),
            ]);
        }
    }

    private function updateCounts(array $categories, array $series, array $tags): void
    {
        foreach ($series as $s) {
            $s->updatePostCount();
        }

        foreach ($tags as $tag) {
            $tag->update([
                'post_count' => $tag->posts()->count(),
            ]);
        }
    }

    private function downloadThumbnail(string $url, string $type): ?string
    {
        $directory = "blog/{$type}";
        Storage::disk('public')->makeDirectory($directory);

        $filename = Str::random(40) . '.jpg';
        $path = "{$directory}/{$filename}";

        try {
            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                Storage::disk('public')->put($path, $response->body());
                return $path;
            }
        } catch (\Exception $e) {
            \Log::warning("Failed to download blog thumbnail: {$url} - {$e->getMessage()}");
        }

        return null;
    }

    private function getPostsData(): array
    {
        return [
            // 제주도 시리즈
            [
                'title' => '제주도 동쪽 여행 코스 추천 - 성산일출봉부터 월정리까지',
                'category' => '여행 가이드',
                'series' => '제주도 완전정복',
                'series_order' => 1,
                'excerpt' => '제주도 동쪽의 핵심 명소들을 하루에 둘러보는 완벽한 코스를 소개합니다.',
                'content' => $this->generateContent('jeju-east'),
                'thumbnail' => 'https://images.unsplash.com/photo-1555992336-fb0d29498b13?w=800',
                'tags' => ['제주도', '자연', '바다', '사진스팟', '일출'],
            ],
            [
                'title' => '제주도 서쪽 여행 코스 추천 - 협재해변부터 오설록까지',
                'category' => '여행 가이드',
                'series' => '제주도 완전정복',
                'series_order' => 2,
                'excerpt' => '에메랄드빛 협재해변과 녹차밭이 아름다운 서쪽 코스를 소개합니다.',
                'content' => $this->generateContent('jeju-west'),
                'thumbnail' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
                'tags' => ['제주도', '바다', '해변', '카페', '힐링'],
            ],
            [
                'title' => '제주도 맛집 베스트 10 - 현지인 추천 로컬 맛집',
                'category' => '맛집 탐방',
                'series' => '제주도 완전정복',
                'series_order' => 3,
                'excerpt' => '관광객들이 모르는 진짜 제주 맛집을 현지인이 추천합니다.',
                'content' => $this->generateContent('jeju-food'),
                'thumbnail' => 'https://images.unsplash.com/photo-1590077428593-a55bb07c4665?w=800',
                'tags' => ['제주도', '맛집', '맛집투어', '혼자여행'],
            ],

            // 서울 시리즈
            [
                'title' => '서울 경복궁 완벽 가이드 - 역사와 함께하는 궁궐 투어',
                'category' => '여행 가이드',
                'series' => '서울 로컬 여행',
                'series_order' => 1,
                'excerpt' => '조선왕조 500년의 역사가 살아있는 경복궁을 제대로 즐기는 방법.',
                'content' => $this->generateContent('seoul-palace'),
                'thumbnail' => 'https://images.unsplash.com/photo-1538485399081-7191377e8241?w=800',
                'tags' => ['서울', '문화탐방', '역사탐방', '사진스팟'],
            ],
            [
                'title' => '서울 야경 명소 베스트 7 - 밤이 더 아름다운 서울',
                'category' => '여행 가이드',
                'series' => '서울 로컬 여행',
                'series_order' => 2,
                'excerpt' => '서울의 밤을 더욱 특별하게 만들어줄 야경 명소를 소개합니다.',
                'content' => $this->generateContent('seoul-night'),
                'thumbnail' => 'https://images.unsplash.com/photo-1517154421773-0529f29ea451?w=800',
                'tags' => ['서울', '야경', '사진스팟', '커플여행'],
            ],
            [
                'title' => '홍대 카페 투어 - 감성 가득한 카페 10선',
                'category' => '맛집 탐방',
                'series' => '서울 로컬 여행',
                'series_order' => 3,
                'excerpt' => '힙스터들의 성지 홍대에서 꼭 가봐야 할 감성 카페들.',
                'content' => $this->generateContent('hongdae-cafe'),
                'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
                'tags' => ['서울', '카페', '카페투어', '사진스팟', '친구여행'],
            ],

            // 부산 시리즈
            [
                'title' => '부산 해운대 완벽 가이드 - 바다와 함께하는 하루',
                'category' => '여행 가이드',
                'series' => '부산 여행 가이드',
                'series_order' => 1,
                'excerpt' => '부산의 대표 해변 해운대를 200% 즐기는 방법.',
                'content' => $this->generateContent('busan-haeundae'),
                'thumbnail' => 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=800',
                'tags' => ['부산', '바다', '해변', '가족여행', '액티비티'],
            ],
            [
                'title' => '감천문화마을 포토 스팟 완벽 정리',
                'category' => '여행 가이드',
                'series' => '부산 여행 가이드',
                'series_order' => 2,
                'excerpt' => '알록달록한 감천문화마을에서 인생샷 남기는 법.',
                'content' => $this->generateContent('busan-gamcheon'),
                'thumbnail' => 'https://images.unsplash.com/photo-1548115184-bc6544d06a58?w=800',
                'tags' => ['부산', '사진스팟', '문화탐방', '혼자여행'],
            ],

            // 일반 게시물
            [
                'title' => '혼자 여행 처음이라면? 초보자를 위한 혼행 팁 10가지',
                'category' => '여행 팁',
                'excerpt' => '혼자 여행이 처음인 분들을 위한 실용적인 팁들을 정리했습니다.',
                'content' => $this->generateContent('solo-travel'),
                'thumbnail' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800',
                'tags' => ['혼자여행', '힐링', '휴양'],
            ],
            [
                'title' => '여행 짐 싸기 체크리스트 - 이것만 있으면 완벽!',
                'category' => '여행 팁',
                'excerpt' => '여행 준비할 때 빠뜨리기 쉬운 것들까지 완벽하게 정리한 체크리스트.',
                'content' => $this->generateContent('packing'),
                'thumbnail' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                'tags' => ['가족여행', '커플여행', '친구여행'],
            ],
            [
                'title' => '경주 1박2일 코스 추천 - 천년 고도의 매력',
                'category' => '여행 가이드',
                'excerpt' => '신라 천년의 역사를 품은 경주를 완벽하게 즐기는 1박2일 코스.',
                'content' => $this->generateContent('gyeongju'),
                'thumbnail' => 'https://images.unsplash.com/photo-1559825481-12a05cc00344?w=800',
                'tags' => ['경주', '역사탐방', '문화탐방', '가족여행'],
            ],
            [
                'title' => '강릉 카페거리 완벽 가이드 - 바다를 보며 커피 한잔',
                'category' => '맛집 탐방',
                'excerpt' => '동해바다를 보며 즐기는 강릉 카페거리 완벽 공략.',
                'content' => $this->generateContent('gangneung-cafe'),
                'thumbnail' => 'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=800',
                'tags' => ['강릉', '카페', '바다', '힐링', '커플여행'],
            ],
            [
                'title' => '전주 한옥마을 먹거리 투어 - 비빔밥 말고도 많다!',
                'category' => '맛집 탐방',
                'excerpt' => '전주 한옥마을에서 꼭 먹어봐야 할 먹거리 총정리.',
                'content' => $this->generateContent('jeonju-food'),
                'thumbnail' => 'https://images.unsplash.com/photo-1590077428593-a55bb07c4665?w=800',
                'tags' => ['전주', '맛집', '맛집투어', '시장투어', '문화탐방'],
            ],
            [
                'title' => '국내 스노클링 명소 베스트 5',
                'category' => '액티비티',
                'excerpt' => '해외 안 나가도 즐길 수 있는 국내 스노클링 스팟.',
                'content' => $this->generateContent('snorkeling'),
                'thumbnail' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
                'tags' => ['액티비티', '스노클링', '바다', '섬', '가족여행'],
            ],
            [
                'title' => '캠핑 초보자를 위한 장비 가이드',
                'category' => '여행 팁',
                'excerpt' => '캠핑 처음 시작하는 분들을 위한 필수 장비 안내.',
                'content' => $this->generateContent('camping'),
                'thumbnail' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800',
                'tags' => ['캠핑', '자연', '가족여행', '친구여행'],
            ],
        ];
    }

    private function generateContent(string $type): string
    {
        $contents = [
            'jeju-east' => <<<HTML
<h2>제주도 동쪽 여행의 하이라이트</h2>
<p>제주도 동쪽은 웅장한 성산일출봉부터 에메랄드빛 월정리 해변까지 다양한 매력을 품고 있습니다. 하루 동안 제주 동쪽의 핵심 명소들을 둘러보는 완벽한 코스를 소개합니다.</p>

<h3>1. 성산일출봉 (소요시간: 2시간)</h3>
<p>유네스코 세계자연유산으로 지정된 성산일출봉은 제주도 여행의 필수 코스입니다. 일출 시간에 맞춰 방문하면 잊지 못할 풍경을 감상할 수 있습니다.</p>
<ul>
<li>입장료: 성인 5,000원 / 청소년 2,500원</li>
<li>추천 시간: 일출 30분 전 도착</li>
<li>소요 시간: 왕복 1시간 30분</li>
</ul>

<h3>2. 만장굴 (소요시간: 1시간)</h3>
<p>세계 최장의 용암동굴 중 하나인 만장굴은 시원한 내부 온도로 여름철에 특히 인기가 좋습니다.</p>

<h3>3. 월정리 해변 (소요시간: 2시간)</h3>
<p>에메랄드빛 바다와 하얀 모래사장이 아름다운 월정리 해변. 카페거리에서 바다를 보며 여유로운 시간을 보내세요.</p>

<blockquote>
<p>💡 여행 팁: 동쪽 코스는 아침 일찍 시작해서 오후에 마무리하는 것을 추천합니다. 일출을 보고 싶다면 성산 근처 숙소에서 1박 하시는 것도 좋습니다.</p>
</blockquote>
HTML,

            'jeju-west' => <<<HTML
<h2>제주도 서쪽의 아름다움</h2>
<p>협재해변의 에메랄드빛 바다와 오설록의 푸른 녹차밭. 제주도 서쪽은 자연의 아름다움을 가득 품고 있습니다.</p>

<h3>1. 협재해변</h3>
<p>비양도를 배경으로 펼쳐지는 에메랄드빛 바다. 수심이 얕아 가족 여행객들에게 인기가 좋습니다.</p>

<h3>2. 오설록 티뮤지엄</h3>
<p>초록빛 녹차밭과 함께 다양한 녹차 체험을 즐길 수 있는 곳입니다. 녹차 아이스크림은 필수!</p>

<h3>3. 카멜리아힐</h3>
<p>동백꽃이 아름다운 정원. 사진 찍기 좋은 포토존이 많습니다.</p>
HTML,

            'jeju-food' => <<<HTML
<h2>현지인이 추천하는 진짜 제주 맛집</h2>
<p>관광객들로 붐비는 유명 맛집 말고, 제주 현지인들이 자주 찾는 로컬 맛집들을 소개합니다.</p>

<h3>흑돼지 전문점</h3>
<p>제주 흑돼지는 본토 돼지와는 다른 특별한 맛이 있습니다. 근고기와 오겹살을 추천합니다.</p>

<h3>해산물 맛집</h3>
<p>신선한 해산물을 맛보고 싶다면 동문시장 주변의 해산물 식당을 추천합니다.</p>

<h3>제주 향토음식</h3>
<ul>
<li>고기국수 - 돼지뼈 육수에 수육을 얹은 국수</li>
<li>몸국 - 돼지고기와 모자반을 넣은 국</li>
<li>빙떡 - 메밀전병에 무채를 넣은 제주 전통음식</li>
</ul>
HTML,

            'seoul-palace' => <<<HTML
<h2>경복궁 완벽 가이드</h2>
<p>조선왕조 500년의 역사가 살아 숨쉬는 경복궁. 한복을 입고 방문하면 무료 입장이 가능합니다.</p>

<h3>관람 코스</h3>
<ol>
<li>광화문 → 흥례문 → 근정전</li>
<li>사정전 → 강녕전 → 교태전</li>
<li>자경전 → 경회루 → 향원정</li>
</ol>

<h3>수문장 교대식</h3>
<p>매일 10시, 14시에 광화문에서 진행됩니다. 조선시대 궁궐 수비의 모습을 재현한 행사입니다.</p>

<blockquote>
<p>💡 한복 대여: 경복궁 주변에 한복 대여점이 많습니다. 4시간 기준 15,000~30,000원</p>
</blockquote>
HTML,

            'seoul-night' => <<<HTML
<h2>서울의 밤이 아름다운 이유</h2>
<p>해가 지면 서울은 또 다른 매력을 보여줍니다. 야경이 아름다운 서울의 명소들을 소개합니다.</p>

<h3>1. N서울타워</h3>
<p>서울 시내를 한눈에 내려다볼 수 있는 최고의 야경 스팟입니다.</p>

<h3>2. 반포대교 달빛무지개분수</h3>
<p>4월~10월 매일 저녁 분수쇼가 진행됩니다.</p>

<h3>3. DDP (동대문디자인플라자)</h3>
<p>자하 하디드가 설계한 미래지향적 건축물. 야간 조명이 특히 아름답습니다.</p>
HTML,

            'hongdae-cafe' => <<<HTML
<h2>홍대 카페 투어</h2>
<p>개성 넘치는 카페들이 가득한 홍대. 감성 충만한 카페 10곳을 소개합니다.</p>

<h3>추천 카페 리스트</h3>
<ul>
<li>○○카페 - 레트로 감성의 인테리어</li>
<li>△△커피 - 직접 로스팅하는 스페셜티 커피</li>
<li>□□하우스 - 옥상에서 바라보는 홍대 뷰</li>
</ul>

<p>주말에는 사람이 많으니 평일 오후를 추천합니다.</p>
HTML,

            'busan-haeundae' => <<<HTML
<h2>해운대 완벽 가이드</h2>
<p>부산하면 해운대! 대한민국 대표 해변 해운대를 200% 즐기는 방법을 알려드립니다.</p>

<h3>해운대 해수욕장</h3>
<p>여름철 피서지로 인기 만점. 넓은 백사장과 다양한 편의시설이 갖춰져 있습니다.</p>

<h3>해운대 시장</h3>
<p>싱싱한 해산물과 다양한 먹거리. 씨앗호떡은 필수!</p>

<h3>동백섬</h3>
<p>해운대 해수욕장 끝에 위치한 동백섬. 해안 산책로가 잘 정비되어 있습니다.</p>
HTML,

            'busan-gamcheon' => <<<HTML
<h2>감천문화마을 포토 가이드</h2>
<p>알록달록한 지붕들이 모여 만든 예술 마을. 인생샷 남기기 좋은 스팟들을 정리했습니다.</p>

<h3>필수 포토 스팟</h3>
<ul>
<li>어린왕자와 사막여우 조형물</li>
<li>물고기 조형물이 있는 골목</li>
<li>마을 전체가 보이는 전망대</li>
</ul>

<h3>방문 팁</h3>
<p>오르막이 많으니 편한 신발을 추천합니다. 마을 입구에서 지도를 꼭 챙기세요!</p>
HTML,

            'solo-travel' => <<<HTML
<h2>혼자 여행 시작하기</h2>
<p>혼자 여행이 처음이라면 막막할 수 있습니다. 혼행 초보자를 위한 실용적인 팁들을 정리했습니다.</p>

<h3>1. 안전한 목적지 선택하기</h3>
<p>첫 혼행이라면 국내부터 시작하는 것을 추천합니다.</p>

<h3>2. 숙소 예약 팁</h3>
<p>위치가 좋고 리뷰가 많은 곳을 선택하세요. 게스트하우스는 다른 여행자들과 교류하기 좋습니다.</p>

<h3>3. 긴급 연락처 저장</h3>
<p>현지 경찰, 대사관, 여행자보험 연락처를 저장해두세요.</p>
HTML,

            'packing' => <<<HTML
<h2>여행 짐 싸기 체크리스트</h2>
<p>여행 준비할 때 빠뜨리기 쉬운 것들까지 완벽하게 정리했습니다.</p>

<h3>필수 준비물</h3>
<ul>
<li>여권 및 신분증</li>
<li>충전기 및 보조배터리</li>
<li>상비약</li>
<li>세면도구</li>
</ul>

<h3>계절별 준비물</h3>
<p>여름: 선크림, 모자, 선글라스<br>
겨울: 핫팩, 보온 내의, 목도리</p>
HTML,

            'gyeongju' => <<<HTML
<h2>경주 1박2일 완벽 코스</h2>
<p>신라 천년의 역사를 품은 경주. 알차게 즐기는 1박2일 코스를 소개합니다.</p>

<h3>첫째 날</h3>
<ul>
<li>불국사 → 석굴암 → 동궁과 월지(안압지)</li>
</ul>

<h3>둘째 날</h3>
<ul>
<li>대릉원 → 첨성대 → 황리단길</li>
</ul>

<p>자전거를 빌려서 돌아다니면 더욱 효율적입니다.</p>
HTML,

            'gangneung-cafe' => <<<HTML
<h2>강릉 카페거리 완벽 가이드</h2>
<p>동해바다를 보며 즐기는 커피 한잔. 강릉은 커피의 도시입니다.</p>

<h3>안목해변 카페거리</h3>
<p>바다 바로 앞에 위치한 카페들. 창가 자리를 추천합니다.</p>

<h3>추천 카페</h3>
<ul>
<li>테라로사 - 강릉 커피의 시작</li>
<li>보헤미안 - 핸드드립 전문</li>
</ul>
HTML,

            'jeonju-food' => <<<HTML
<h2>전주 한옥마을 먹거리 투어</h2>
<p>비빔밥만 있는 줄 알았다면 오산! 전주의 다양한 먹거리를 소개합니다.</p>

<h3>꼭 먹어봐야 할 것들</h3>
<ul>
<li>전주비빔밥 - 물론 이건 필수!</li>
<li>콩나물국밥 - 해장에 최고</li>
<li>PNB 초코파이 - 쫀득한 수제 초코파이</li>
<li>한옥마을 길거리 음식 - 꼬치, 호떡 등</li>
</ul>
HTML,

            'snorkeling' => <<<HTML
<h2>국내 스노클링 명소</h2>
<p>해외까지 나가지 않아도 즐길 수 있는 국내 스노클링 스팟을 소개합니다.</p>

<h3>1. 제주 함덕해변</h3>
<p>수심이 얕고 물이 맑아 초보자에게 적합합니다.</p>

<h3>2. 울릉도 학포해변</h3>
<p>투명한 물과 다양한 해양생물을 볼 수 있습니다.</p>

<h3>3. 부산 송정해변</h3>
<p>접근성이 좋고 장비 대여가 편리합니다.</p>
HTML,

            'camping' => <<<HTML
<h2>캠핑 시작하기</h2>
<p>캠핑을 처음 시작하는 분들을 위한 필수 장비와 팁을 정리했습니다.</p>

<h3>필수 장비</h3>
<ul>
<li>텐트 - 처음엔 대여로 시작하세요</li>
<li>침낭 및 매트</li>
<li>랜턴</li>
<li>버너 및 조리도구</li>
</ul>

<h3>초보자 추천 캠핑장</h3>
<p>시설이 잘 갖춰진 오토캠핑장부터 시작하는 것을 추천합니다.</p>
HTML,
        ];

        return $contents[$type] ?? '<p>콘텐츠가 준비중입니다.</p>';
    }
}
