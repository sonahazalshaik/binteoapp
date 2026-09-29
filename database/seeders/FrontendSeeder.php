<?php

namespace Database\Seeders;

use App\Models\Frontend;
use Illuminate\Database\Seeder;

class FrontendSeeder extends Seeder
{
    public function run(): void
    {
        $tempname = activeTemplateName();

        Frontend::updateOrCreate(
            ['data_keys' => 'about.content', 'tempname' => $tempname],
            [
                'data_values' => [
                    'title' => 'Empowering Creators Worldwide',
                    'image' => 'about_hero.png',
                    'content' => 'Welcome to Binteo, the next-generation video sharing platform designed for creators, by creators. Our mission is to provide a seamless, high-performance environment where your stories can reach millions. We believe in the power of digital storytelling to connect people and inspire change.',
                    'title1' => 'Our Mission',
                    'image1' => 'about_collaboration.png',
                    'content1' => 'To democratize video distribution and provide tools that empower every voice to be heard. We believe in transparency, community, and innovation as the core pillars of a healthy digital ecosystem.',
                    'title2' => 'Our Vision',
                    'image2' => 'about_hero.png',
                    'content2' => 'To become the global hub for digital storytelling, where high-quality content meets a passionate audience without barriers. We strive to lead the industry in video technology and creator compensation.',
                    'title3' => 'Community First',
                    'image3' => 'about_collaboration.png',
                    'content3' => 'We prioritize our community above all else. Every feature we build is designed to enhance the experience for both creators and viewers, ensuring a vibrant and supportive environment.',
                    'title4' => 'Innovation Driven',
                    'image4' => 'about_hero.png',
                    'content4' => 'Constant innovation is in our DNA. From ultra-high-definition streaming to advanced monetization tools, we are always pushing the boundaries of what is possible in online video.',
                ],
            ]
        );

        Frontend::updateOrCreate(
            ['data_keys' => 'policy_pages.element', 'slug' => 'privacy-policy', 'tempname' => $tempname],
            [
                'data_values' => [
                    'title' => 'Privacy Policy',
                    'content' => '<h3>1. Information Collection</h3><p>We collect information you provide directly to us when you create an account, upload videos, or communicate with us. This includes your name, email address, phone number, and profile information.</p><h3>2. Use of Information</h3><p>We use the information we collect to provide, maintain, and improve our services, to personalize your experience, and to communicate with you about updates, promotions, and platform changes.</p><h3>3. Data Protection</h3><p>We implement a variety of security measures to maintain the safety of your personal information. Your data is encrypted in transit and at rest using industry-standard protocols.</p><h3>4. Data Sharing</h3><p>We do not sell, trade, or rent your personal information to third parties. We may share anonymized aggregate data for analytics purposes.</p><h3>5. Cookies</h3><p>We use cookies to enhance your experience on our platform. You can choose to disable cookies through your browser settings, though some features may not function properly.</p><h3>6. Your Rights</h3><p>You have the right to access, update, or delete your personal information at any time through your account settings. You may also request a copy of your data by contacting us.</p><h3>7. Contact</h3><p>For privacy-related inquiries, contact us at: handgnextgenpvtltd@gmail.com</p>',
                ],
            ]
        );

        Frontend::updateOrCreate(
            ['data_keys' => 'policy_pages.element', 'slug' => 'copyright-policy', 'tempname' => $tempname],
            [
                'data_values' => [
                    'title' => 'Copyright Policy',
                    'content' => '<p>Binteo respects intellectual property rights and expects all users to do the same.</p><h3>1. CONTENT OWNERSHIP</h3><p>Creators retain full copyright ownership of original content they upload to Binteo. By uploading, creators grant Binteo a limited, non-exclusive license to host, display, and distribute content on the platform only.</p><h3>2. ORIGINAL CONTENT REQUIREMENT</h3><p>Binteo strictly requires ALL uploaded content to be original. Content you personally created from scratch is allowed. Content where you have full written permission from the copyright holder is allowed. Content using properly licensed music, images, or clips from Binteo library or licensed sources is allowed. Re-uploading your own videos from YouTube, Facebook, Instagram, or any other platform is NOT allowed. Downloading and re-uploading other creators videos is NOT allowed.</p><h3>3. WHAT COPYRIGHT INFRINGEMENT MEANS</h3><p>Using someone else\'s video, music, image, or creative work without permission is Copyright Infringement. Giving credit does NOT automatically give you the right to use others\' content. Content being publicly available online does NOT mean it is free to use.</p><h3>4. REPORTING INFRINGEMENT</h3><p>If you believe your copyright has been infringed, please contact us with the following information: your contact details, identification of the copyrighted work, the URL of the infringing content, and a statement of good faith belief.</p><h3>5. CONSEQUENCES</h3><p>First violation: Content removed + warning. Second violation: 7-day suspension. Third violation: Permanent account termination. Repeated or egregious violations may result in immediate permanent termination.</p>',
                ],
            ]
        );

        Frontend::updateOrCreate(
            ['data_keys' => 'policy_pages.element', 'slug' => 'terms-of-service', 'tempname' => $tempname],
            [
                'data_values' => [
                    'title' => 'Terms of Service',
                    'content' => '<h3>1. ACCEPTANCE OF TERMS</h3><p>By accessing or using the Binteo platform (website, mobile application, and all related services), you agree to be bound by these Terms and Conditions.</p><h3>2. DEFINITIONS</h3><p>"Binteo," "Platform," "we," "us," or "our" refers to H&amp;G Nextgen Private Limited. "User," "you," or "your" refers to any person using Binteo. "Content" means videos, images, text, audio, and any material uploaded to Binteo.</p><h3>3. ELIGIBILITY</h3><p>You must be at least 13 years old to use Binteo. Users under 18 must have parental consent.</p><h3>4. USER ACCOUNTS</h3><p>You are responsible for maintaining the confidentiality of your account credentials. You must provide accurate information during registration. Binteo reserves the right to suspend or terminate accounts that violate these Terms.</p><h3>5. USER CONDUCT</h3><p>You agree NOT to upload content that violates laws, infringes copyrights, or harms others. Do not engage in harassment, hate speech, bullying, or threatening behavior. Do not upload sexually explicit, violent, or harmful content involving minors. Do not spam, phish, or distribute malware. Do not impersonate others or misrepresent your affiliation.</p><h3>6. CONTENT OWNERSHIP AND LICENSE</h3><p>You retain ownership of content you upload. By uploading, you grant Binteo a worldwide, non-exclusive, royalty-free license to use, reproduce, distribute, and display your content on the platform.</p><h3>7. TERMINATION</h3><p>Binteo may terminate or suspend your account at any time for violations of these Terms, with or without prior notice.</p><h3>8. DISCLAIMER</h3><p>The platform is provided "as is" without warranties of any kind. Binteo is not liable for any damages arising from the use of the platform.</p><h3>9. GOVERNING LAW</h3><p>These Terms shall be governed by the laws of India. Any disputes shall be resolved in the courts of competent jurisdiction.</p><h3>10. CONTACT</h3><p>For questions about these Terms, contact us at: handgnextgenpvtltd@gmail.com</p>',
                ],
            ]
        );

        Frontend::updateOrCreate(
            ['data_keys' => 'community_guidelines.content', 'tempname' => $tempname],
            [
                'data_values' => [
                    'title' => 'Community Guidelines',
                    'content' => '<h3>1. RESPECT AND KINDNESS</h3><p>Treat others with respect. No harassment, bullying, or hate speech. Do not target individuals or groups based on race, religion, gender, nationality, disability, or sexual orientation. Respect differing opinions and engage constructively.</p><h3>2. PROHIBITED CONTENT</h3><p>The following types of content are strictly prohibited:</p><h4>2.1 Hate Speech and Discrimination</h4><p>Content that promotes violence or hatred against individuals or groups. Content that incites discrimination based on protected characteristics.</p><h4>2.2 Violence and Graphic Content</h4><p>Violent or gory content intended to shock or disgust. Content depicting animal abuse or cruelty. Content glorifying or promoting self-harm or suicide.</p><h4>2.3 Adult and Sexual Content</h4><p>Sexually explicit content, nudity, or pornography. Content that sexualizes minors in any way. Content promoting sexual services or escort services.</p><h4>2.4 Child Safety</h4><p>ZERO TOLERANCE: Any content that endangers or exploits minors will result in immediate account termination and reporting to authorities. Do not upload content featuring minors in dangerous, sexual, or exploitative situations. Do not solicit personal information from minors.</p><h4>2.5 Dangerous and Illegal Activities</h4><p>Content promoting terrorism, violence, or criminal activities. Instructional content on making weapons, explosives, or drugs. Content promoting dangerous challenges or pranks that could cause harm.</p><h4>2.6 Misinformation and Scams</h4><p>Deliberately misleading content that could cause harm. Medical misinformation that contradicts established health guidance. Election misinformation or manipulation. Scams, phishing, or fraudulent schemes.</p><h3>3. SPAM AND DECEPTIVE PRACTICES</h3><p>Do not spam comments, messages, or uploads. Do not engage in clickbait or misleading metadata (titles, thumbnails, descriptions). Do not artificially inflate views, likes, or subscribers. Do not impersonate others or misrepresent your identity.</p><h3>4. COPYRIGHT AND INTELLECTUAL PROPERTY</h3><p>Only upload content you own or have permission to use. Respect copyright. Do not re-upload others videos without authorization. Do not use copyrighted music, images, or video clips without proper rights or licenses. Fair use does not give blanket permission to use others content.</p><h3>5. ORIGINAL CONTENT REQUIREMENT</h3><p>Binteo values fresh, original content created specifically for this platform. Do not re-upload content previously published on other platforms (YouTube, Facebook, Instagram, etc.) without significant modification or value addition. Compilations, reactions, and commentary videos must add substantial original value.</p><h3>6. PRIVACY AND PERSONAL INFORMATION</h3><p>Do not share others private information (doxxing) without consent. Do not post content that invades someones privacy. Respect individuals right to privacy.</p><h3>7. ENFORCEMENT</h3><p>Violations of these Guidelines may result in: Content removal, Warning issued to your account, Temporary suspension of uploading or monetization, Permanent account termination (for severe or repeated violations), Reporting to law enforcement (for illegal content).</p><h3>8. REPORTING VIOLATIONS</h3><p>If you see content that violates these Guidelines, report it using the in-app reporting feature. All reports are reviewed by our moderation team. False or abusive reports may result in penalties.</p><h3>9. APPEALS</h3><p>If your content is removed or your account is suspended, you may appeal the decision by contacting support@binteo.in with your account username, the content or action in question, and reason why you believe the decision was incorrect. Binteo reserves the right to make final decisions on all content moderation matters.</p><h3>10. UPDATES TO GUIDELINES</h3><p>Binteo may update these Community Guidelines from time to time. Continued use of the platform after updates constitutes acceptance of the revised Guidelines.</p>',
                ],
            ]
        );

        Frontend::updateOrCreate(
            ['data_keys' => 'marketplace_terms.content', 'tempname' => $tempname],
            [
                'data_values' => [
                    'title' => 'Official Marketplace Terms & Guidelines',
                    'subtitle' => 'Professional Standards & Agreements',
                    'content' => '<h3>1. Introduction</h3><p>The Binteo Profile Marketplace is a specialized platform designed to bridge the gap between creative talent (Actors, Influencers) and strategic partners (Investors, Producers). By creating a profile, users agree to abide by the following terms and professional standards.</p><h3>2. Role-Specific Guidelines</h3><h4>A. Actors &amp; Influencers</h4><p><strong>Accuracy:</strong> All information provided must be authentic. Any misrepresentation will lead to immediate profile suspension. <strong>Content Ownership:</strong> Users must hold legal rights to all media uploaded. Intellectual property violations are strictly prohibited.</p><h4>B. Investors &amp; Producers</h4><p><strong>Professional Conduct:</strong> Maintain professional decorum when contacting talent. <strong>Transparency:</strong> Provide clear terms regarding the scope of work and expectations.</p><h3>3. General Terms of Use</h3><p><strong>Verification:</strong> Binteo reserves the right to verify any profile. <strong>Direct Transactions:</strong> Binteo acts as a discovery platform. Agreements made outside the platform are at users own risk. <strong>Prohibited Content:</strong> Profiles must not contain nudity, violence, hate speech, or politically inflammatory content. <strong>Data Privacy:</strong> Scraping data or using contact information for unsolicited spam is strictly forbidden.</p><h3>4. Payments &amp; Service Fees</h3><p>Any fees paid for premium marketplace features are non-refundable unless otherwise stated. Binteo may charge a service fee for successful collaborations facilitated through the platform.</p><h3>5. Dispute Resolution</h3><p>Disputes between users should first be resolved amicably. If unresolved, Binteo may mediate at its discretion. Binteo is not liable for disputes arising from user-to-user interactions.</p><h3>6. Termination</h3><p>Binteo reserves the right to remove any profile that violates these terms without prior notice. Users may delete their marketplace profile at any time.</p><h3>7. Modifications</h3><p>Binteo may update these Marketplace Terms at any time. Continued use of the marketplace after changes constitutes acceptance of the new terms.</p>',
                ],
            ]
        );
    }
}
