<?php

/**
 * College Sports Management System
 * Premium Certificate Generation - High Fidelity Layout
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Certificates';
$current_page = 'certificates';

// High-fidelity registries
$players = $conn->query("SELECT id, name, register_number FROM players WHERE status = 'active' ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$sports = $conn->query("SELECT id, sport_name FROM sports_categories WHERE status = 'active' ORDER BY sport_name")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $player_id = intval($_POST['player_id']);
    $certificate_type = $_POST['certificate_type'];
    $sport_id = intval($_POST['sport_id']);
    $achievement = sanitize($_POST['achievement']);
    $issue_date = $_POST['issue_date'];

    $stmt = $conn->prepare("INSERT INTO certificates (player_id, certificate_type, sport_id, achievement, issue_date, generated_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissi", $player_id, $certificate_type, $sport_id, $achievement, $issue_date, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $cert_id = $stmt->insert_id;
        logActivity($conn, 'create', 'certificates', $cert_id, "Generated credential: $certificate_type");
        setSuccess('Credential initialized successfully.');
        header("Location: generate_certificate.php?preview=$cert_id");
        exit();
    }
}

// Credential Preview
$preview_cert = null;
if (isset($_GET['preview'])) {
    $cert_id = intval($_GET['preview']);
    $preview_cert = $conn->query("SELECT c.*, p.name as player_name, p.register_number, 
                                   s.sport_name, u.full_name as issued_by
                                   FROM certificates c
                                   JOIN players p ON c.player_id = p.id
                                   LEFT JOIN sports_categories s ON c.sport_id = s.id
                                   JOIN users u ON c.generated_by = u.id
                                   WHERE c.id = $cert_id")->fetch_assoc();
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Generate Certificates</h1>
            <p class="subtitle-text">Create official achievement and participation certificates</p>
        </div>
        <div class="header-actions">
            <?php if ($preview_cert): ?>
                <a href="generate_certificate.php" class="btn-reset-light">Create New</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <?php if ($preview_cert): ?>
                <!-- PREMIUM CERTIFICATE PREVIEW -->
                <div class="glass-card" style="padding: 0; overflow: hidden; border: none;">
                    <div id="certificate-print-area" class="certificate-paper certificate-<?php echo strtolower($preview_cert['certificate_type']); ?>">
                        <?php if ($preview_cert['certificate_type'] === 'Winner'): ?>
                            <!-- WINNER CERTIFICATE DESIGN -->
                            <div class="cert-winner-border">
                                <div class="cert-content-winner">
                                    <div class="winner-header">
                                        <div class="trophy-icon">🏆</div>
                                        <div class="gold-ribbon">FIRST PLACE</div>
                                        <h1 class="cert-title-winner">CHAMPION</h1>
                                        <span class="cert-org">College Athletics Department</span>
                                    </div>

                                    <div class="cert-body-winner">
                                        <p class="cert-text-elegant">This certificate is proudly presented to</p>
                                        <h2 class="cert-name-winner"><?php echo htmlspecialchars($preview_cert['player_name']); ?></h2>
                                        <p class="cert-identity-gold"><?php echo $preview_cert['register_number']; ?></p>

                                        <div class="achievement-box-winner">
                                            <p class="cert-text-elegant">For achieving First Place in</p>
                                            <h3 class="cert-sport-winner"><?php echo htmlspecialchars($preview_cert['sport_name']); ?></h3>
                                            <?php if ($preview_cert['achievement']): ?>
                                                <div class="cert-achievement-winner">
                                                    <?php echo nl2br(htmlspecialchars($preview_cert['achievement'])); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="cert-footer-winner">
                                        <div class="signature-block-winner">
                                            <div class="sig-line-winner"></div>
                                            <span class="sig-label-winner">Department Head</span>
                                        </div>
                                        <div class="cert-seal-winner">
                                            <div class="seal-inner">OFFICIAL<br>SEAL</div>
                                        </div>
                                        <div class="signature-block-winner">
                                            <span class="cert-date-winner"><?php echo date('M d, Y', strtotime($preview_cert['issue_date'])); ?></span>
                                            <div class="sig-line-winner"></div>
                                            <span class="sig-label-winner">Date Awarded</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php elseif ($preview_cert['certificate_type'] === 'Runner-Up'): ?>
                            <!-- RUNNER-UP CERTIFICATE DESIGN -->
                            <div class="cert-runnerup-border">
                                <div class="cert-content-runnerup">
                                    <div class="runnerup-header">
                                        <div class="medal-icon">🥈</div>
                                        <div class="silver-ribbon">SECOND PLACE</div>
                                        <h1 class="cert-title-runnerup">RUNNER-UP</h1>
                                        <span class="cert-org-silver">College Athletics Department</span>
                                    </div>

                                    <div class="cert-body-runnerup">
                                        <p class="cert-text-silver">This certificate honors</p>
                                        <h2 class="cert-name-runnerup"><?php echo htmlspecialchars($preview_cert['player_name']); ?></h2>
                                        <p class="cert-identity-silver"><?php echo $preview_cert['register_number']; ?></p>

                                        <div class="achievement-box-runnerup">
                                            <p class="cert-text-silver">For securing Second Place in</p>
                                            <h3 class="cert-sport-runnerup"><?php echo htmlspecialchars($preview_cert['sport_name']); ?></h3>
                                            <?php if ($preview_cert['achievement']): ?>
                                                <div class="cert-achievement-runnerup">
                                                    <?php echo nl2br(htmlspecialchars($preview_cert['achievement'])); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="cert-footer-runnerup">
                                        <div class="signature-block-runnerup">
                                            <div class="sig-line-runnerup"></div>
                                            <span class="sig-label-runnerup">Department Head</span>
                                        </div>
                                        <div class="cert-seal-runnerup">
                                            <div class="seal-inner-silver">OFFICIAL<br>SEAL</div>
                                        </div>
                                        <div class="signature-block-runnerup">
                                            <span class="cert-date-runnerup"><?php echo date('M d, Y', strtotime($preview_cert['issue_date'])); ?></span>
                                            <div class="sig-line-runnerup"></div>
                                            <span class="sig-label-runnerup">Date Awarded</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php elseif ($preview_cert['certificate_type'] === 'Achievement'): ?>
                            <!-- ACHIEVEMENT CERTIFICATE DESIGN -->
                            <div class="cert-achievement-border">
                                <div class="cert-content-achievement">
                                    <div class="achievement-header">
                                        <div class="star-icon">⭐</div>
                                        <h1 class="cert-title-achievement">ACHIEVEMENT</h1>
                                        <div class="decorative-line"></div>
                                        <span class="cert-org-achievement">College Athletics Department</span>
                                    </div>

                                    <div class="cert-body-achievement">
                                        <p class="cert-text-achievement">This certificate recognizes</p>
                                        <h2 class="cert-name-achievement"><?php echo htmlspecialchars($preview_cert['player_name']); ?></h2>
                                        <p class="cert-identity-achievement"><?php echo $preview_cert['register_number']; ?></p>

                                        <div class="achievement-box-special">
                                            <p class="cert-text-achievement">For outstanding achievement in</p>
                                            <h3 class="cert-sport-achievement"><?php echo htmlspecialchars($preview_cert['sport_name']); ?></h3>
                                            <?php if ($preview_cert['achievement']): ?>
                                                <div class="cert-achievement-special">
                                                    <?php echo nl2br(htmlspecialchars($preview_cert['achievement'])); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="cert-footer-achievement">
                                        <div class="signature-block-achievement">
                                            <div class="sig-line-achievement"></div>
                                            <span class="sig-label-achievement">Department Head</span>
                                        </div>
                                        <div class="cert-seal-achievement">
                                            <div class="seal-inner-achievement">★</div>
                                        </div>
                                        <div class="signature-block-achievement">
                                            <span class="cert-date-achievement"><?php echo date('M d, Y', strtotime($preview_cert['issue_date'])); ?></span>
                                            <div class="sig-line-achievement"></div>
                                            <span class="sig-label-achievement">Date Issued</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php else: ?>
                            <!-- PARTICIPATION CERTIFICATE DESIGN -->
                            <div class="cert-participation-border">
                                <div class="cert-content-participation">
                                    <div class="participation-header">
                                        <span class="cert-org-participation">COLLEGE ATHLETICS DEPARTMENT</span>
                                        <h1 class="cert-title-participation">CERTIFICATE</h1>
                                        <span class="cert-type-participation">OF PARTICIPATION</span>
                                    </div>

                                    <div class="cert-body-participation">
                                        <p class="cert-text-participation">This certifies that</p>
                                        <h2 class="cert-name-participation"><?php echo htmlspecialchars($preview_cert['player_name']); ?></h2>
                                        <p class="cert-identity-participation"><?php echo $preview_cert['register_number']; ?></p>

                                        <div class="achievement-box-participation">
                                            <p class="cert-text-participation">Has actively participated in</p>
                                            <h3 class="cert-sport-participation"><?php echo htmlspecialchars($preview_cert['sport_name']); ?></h3>
                                            <?php if ($preview_cert['achievement']): ?>
                                                <div class="cert-achievement-participation">
                                                    <?php echo nl2br(htmlspecialchars($preview_cert['achievement'])); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="cert-footer-participation">
                                        <div class="signature-block-participation">
                                            <div class="sig-line-participation"></div>
                                            <span class="sig-label-participation">Department Head</span>
                                        </div>
                                        <div class="cert-stamp-participation">
                                            <div class="stamp-inner-participation">OFFICIAL SEAL</div>
                                        </div>
                                        <div class="signature-block-participation">
                                            <span class="cert-date-participation"><?php echo date('M d, Y', strtotime($preview_cert['issue_date'])); ?></span>
                                            <div class="sig-line-participation"></div>
                                            <span class="sig-label-participation">Date</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="cert-actions p-8" style="background: white; border-top: 1px solid #f1f5f9; text-align: center;">
                        <button onclick="window.print()" class="btn-premium-search" style="padding: 15px 40px;">
                            <img src="<?php echo $icons['reports']; ?>" style="width: 18px; filter: brightness(0) invert(1); margin-right: 10px;">
                            Print Certificate
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <!-- GENERATION FORM -->
                <div class="glass-card">
                    <form method="POST" class="premium-form">
                        <div class="form-grid">
                            <div class="form-column">
                                <h3 class="form-section-title">Select Player</h3>
                                <div class="premium-field">
                                    <label class="field-label">Player Name</label>
                                    <select name="player_id" class="premium-select" required>
                                        <option value="">-- Select Player --</option>
                                        <?php foreach ($players as $player): ?>
                                            <option value="<?php echo $player['id']; ?>">
                                                <?php echo htmlspecialchars($player['name']); ?> (<?php echo $player['register_number']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="premium-field">
                                    <label class="field-label">Sport</label>
                                    <select name="sport_id" class="premium-select" required>
                                        <option value="">-- Select Sport --</option>
                                        <?php foreach ($sports as $sport): ?>
                                            <option value="<?php echo $sport['id']; ?>"><?php echo $sport['sport_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-column">
                                <h3 class="form-section-title">Certificate Details</h3>
                                <div class="premium-field">
                                    <label class="field-label">Certificate Type</label>
                                    <select name="certificate_type" class="premium-select" required>
                                        <option value="Participation">Participation</option>
                                        <option value="Achievement">Achievement</option>
                                        <option value="Winner">Winner (1st Place)</option>
                                        <option value="Runner-Up">Runner-Up (2nd Place)</option>
                                    </select>
                                </div>

                                <div class="premium-field">
                                    <label class="field-label">Issue Date</label>
                                    <input type="date" name="issue_date" class="premium-input" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="premium-divider"></div>

                        <div class="premium-field">
                            <label class="field-label">Achievement Description</label>
                            <textarea name="achievement" class="premium-input" rows="3" placeholder="Describe the achievement or performance..."></textarea>
                        </div>

                        <div class="form-footer mt-8">
                            <button type="submit" class="btn-premium-search" style="min-width: 220px;">Generate Certificate</button>
                            <a href="generate_certificate.php" class="btn-reset-light" style="margin-left: 10px;">Reset Input</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <div class="side-column">
            <div class="glass-card">
                <h3 class="field-label mb-6 block">Guidelines</h3>
                <div class="stats-mini">
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        Certificates are officially logged and can be reprinted anytime. Ensure all information is accurate before generating.
                    </p>
                </div>
            </div>

            <?php if (!$preview_cert): ?>
                <div class="glass-card mt-8">
                    <h3 class="field-label mb-4 block">Important Notes</h3>
                    <ul style="padding-left: 15px; font-size: 12px; color: var(--text-secondary); display: flex; flex-direction: column; gap: 8px;">
                        <li>Select the correct certificate type</li>
                        <li>Description will be formatted automatically</li>
                        <li>Digital signatures are applied</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Base Certificate Styles */
    .certificate-paper {
        background: white;
        padding: 40px;
        color: #1e293b;
        font-family: 'Times New Roman', serif;
        min-height: 800px;
    }

    /* WINNER CERTIFICATE - GOLD THEME */
    .cert-winner-border {
        border: 20px solid;
        border-image: linear-gradient(135deg, #ffd700, #ffed4e, #ffd700) 1;
        padding: 10px;
        background: linear-gradient(135deg, #fffbf0, #fff8e1);
        box-shadow: inset 0 0 60px rgba(255, 215, 0, 0.2);
    }

    .cert-content-winner {
        border: 3px double #d4af37;
        padding: 50px;
        background: white;
        text-align: center;
    }

    .winner-header {
        margin-bottom: 40px;
    }

    .trophy-icon {
        font-size: 80px;
        margin-bottom: 15px;
    }

    .gold-ribbon {
        background: linear-gradient(90deg, #ffd700, #ffed4e, #ffd700);
        color: #000;
        padding: 8px 30px;
        font-weight: 900;
        letter-spacing: 4px;
        display: inline-block;
        margin-bottom: 15px;
        border-radius: 5px;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
    }

    .cert-title-winner {
        font-size: 64px;
        font-weight: 900;
        color: #d4af37;
        letter-spacing: 8px;
        margin: 10px 0;
        text-shadow: 2px 2px 4px rgba(212, 175, 55, 0.3);
    }

    .cert-org {
        font-size: 14px;
        color: #64748b;
        letter-spacing: 2px;
        font-weight: 700;
    }

    .cert-body-winner {
        margin: 40px 0;
    }

    .cert-text-elegant {
        font-size: 18px;
        font-style: italic;
        color: #475569;
        margin: 15px 0;
    }

    .cert-name-winner {
        font-size: 48px;
        font-weight: 900;
        color: #d4af37;
        margin: 20px 0;
        text-transform: uppercase;
    }

    .cert-identity-gold {
        font-size: 16px;
        color: #94a3b8;
        font-weight: 700;
    }

    .achievement-box-winner {
        margin-top: 40px;
        padding: 30px;
        background: linear-gradient(135deg, #fffbf0, #ffffff);
        border-radius: 15px;
        border: 2px solid #ffd700;
    }

    .cert-sport-winner {
        font-size: 32px;
        font-weight: 800;
        color: #d4af37;
        margin: 15px 0;
        text-transform: uppercase;
    }

    .cert-achievement-winner {
        font-size: 14px;
        color: #64748b;
        font-style: italic;
        margin-top: 20px;
        line-height: 1.8;
    }

    .cert-footer-winner {
        margin-top: 60px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .signature-block-winner {
        width: 180px;
    }

    .sig-line-winner {
        border-top: 2px solid #d4af37;
        margin-bottom: 8px;
    }

    .sig-label-winner {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .cert-date-winner {
        font-size: 14px;
        font-weight: 900;
        color: #d4af37;
        display: block;
        margin-bottom: 8px;
    }

    .cert-seal-winner {
        width: 100px;
        height: 100px;
        border: 6px double #d4af37;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle, #fffbf0, #ffd700);
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
    }

    .seal-inner {
        font-size: 11px;
        font-weight: 900;
        text-align: center;
        line-height: 1.3;
        color: #000;
    }

    /* RUNNER-UP CERTIFICATE - SILVER THEME */
    .cert-runnerup-border {
        border: 20px solid;
        border-image: linear-gradient(135deg, #c0c0c0, #e8e8e8, #c0c0c0) 1;
        padding: 10px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        box-shadow: inset 0 0 60px rgba(192, 192, 192, 0.2);
    }

    .cert-content-runnerup {
        border: 3px double #a8a8a8;
        padding: 50px;
        background: white;
        text-align: center;
    }

    .runnerup-header {
        margin-bottom: 40px;
    }

    .medal-icon {
        font-size: 80px;
        margin-bottom: 15px;
    }

    .silver-ribbon {
        background: linear-gradient(90deg, #c0c0c0, #e8e8e8, #c0c0c0);
        color: #000;
        padding: 8px 30px;
        font-weight: 900;
        letter-spacing: 4px;
        display: inline-block;
        margin-bottom: 15px;
        border-radius: 5px;
        box-shadow: 0 4px 15px rgba(192, 192, 192, 0.4);
    }

    .cert-title-runnerup {
        font-size: 64px;
        font-weight: 900;
        color: #a8a8a8;
        letter-spacing: 8px;
        margin: 10px 0;
        text-shadow: 2px 2px 4px rgba(168, 168, 168, 0.3);
    }

    .cert-org-silver {
        font-size: 14px;
        color: #64748b;
        letter-spacing: 2px;
        font-weight: 700;
    }

    .cert-body-runnerup {
        margin: 40px 0;
    }

    .cert-text-silver {
        font-size: 18px;
        font-style: italic;
        color: #475569;
        margin: 15px 0;
    }

    .cert-name-runnerup {
        font-size: 48px;
        font-weight: 900;
        color: #a8a8a8;
        margin: 20px 0;
        text-transform: uppercase;
    }

    .cert-identity-silver {
        font-size: 16px;
        color: #94a3b8;
        font-weight: 700;
    }

    .achievement-box-runnerup {
        margin-top: 40px;
        padding: 30px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 15px;
        border: 2px solid #c0c0c0;
    }

    .cert-sport-runnerup {
        font-size: 32px;
        font-weight: 800;
        color: #a8a8a8;
        margin: 15px 0;
        text-transform: uppercase;
    }

    .cert-achievement-runnerup {
        font-size: 14px;
        color: #64748b;
        font-style: italic;
        margin-top: 20px;
        line-height: 1.8;
    }

    .cert-footer-runnerup {
        margin-top: 60px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .signature-block-runnerup {
        width: 180px;
    }

    .sig-line-runnerup {
        border-top: 2px solid #a8a8a8;
        margin-bottom: 8px;
    }

    .sig-label-runnerup {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .cert-date-runnerup {
        font-size: 14px;
        font-weight: 900;
        color: #a8a8a8;
        display: block;
        margin-bottom: 8px;
    }

    .cert-seal-runnerup {
        width: 100px;
        height: 100px;
        border: 6px double #a8a8a8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle, #f8f9fa, #c0c0c0);
        box-shadow: 0 0 20px rgba(192, 192, 192, 0.5);
    }

    .seal-inner-silver {
        font-size: 11px;
        font-weight: 900;
        text-align: center;
        line-height: 1.3;
        color: #000;
    }

    /* ACHIEVEMENT CERTIFICATE - PURPLE THEME */
    .cert-achievement-border {
        border: 15px solid;
        border-image: linear-gradient(135deg, #8b5cf6, #a78bfa, #8b5cf6) 1;
        padding: 10px;
        background: linear-gradient(135deg, #faf5ff, #f3e8ff);
        box-shadow: inset 0 0 60px rgba(139, 92, 246, 0.1);
    }

    .cert-content-achievement {
        border: 2px solid #8b5cf6;
        padding: 50px;
        background: white;
        text-align: center;
    }

    .achievement-header {
        margin-bottom: 40px;
    }

    .star-icon {
        font-size: 60px;
        color: #8b5cf6;
        margin-bottom: 15px;
    }

    .cert-title-achievement {
        font-size: 58px;
        font-weight: 900;
        color: #8b5cf6;
        letter-spacing: 10px;
        margin: 15px 0;
    }

    .decorative-line {
        width: 200px;
        height: 3px;
        background: linear-gradient(90deg, transparent, #8b5cf6, transparent);
        margin: 20px auto;
    }

    .cert-org-achievement {
        font-size: 13px;
        color: #64748b;
        letter-spacing: 2px;
        font-weight: 700;
    }

    .cert-body-achievement {
        margin: 40px 0;
    }

    .cert-text-achievement {
        font-size: 17px;
        font-style: italic;
        color: #475569;
        margin: 15px 0;
    }

    .cert-name-achievement {
        font-size: 44px;
        font-weight: 900;
        color: #7c3aed;
        margin: 20px 0;
    }

    .cert-identity-achievement {
        font-size: 15px;
        color: #94a3b8;
        font-weight: 700;
    }

    .achievement-box-special {
        margin-top: 35px;
        padding: 25px;
        background: linear-gradient(135deg, #faf5ff, #ffffff);
        border-radius: 12px;
        border: 2px dashed #a78bfa;
    }

    .cert-sport-achievement {
        font-size: 30px;
        font-weight: 800;
        color: #8b5cf6;
        margin: 15px 0;
    }

    .cert-achievement-special {
        font-size: 13px;
        color: #64748b;
        font-style: italic;
        margin-top: 15px;
        line-height: 1.7;
    }

    .cert-footer-achievement {
        margin-top: 60px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .signature-block-achievement {
        width: 170px;
    }

    .sig-line-achievement {
        border-top: 2px solid #8b5cf6;
        margin-bottom: 8px;
    }

    .sig-label-achievement {
        font-size: 10px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .cert-date-achievement {
        font-size: 13px;
        font-weight: 900;
        color: #8b5cf6;
        display: block;
        margin-bottom: 8px;
    }

    .cert-seal-achievement {
        width: 90px;
        height: 90px;
        border: 4px solid #8b5cf6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle, #faf5ff, #a78bfa);
        box-shadow: 0 0 15px rgba(139, 92, 246, 0.3);
    }

    .seal-inner-achievement {
        font-size: 40px;
        color: #8b5cf6;
    }

    /* PARTICIPATION CERTIFICATE - CLASSIC THEME */
    .cert-participation-border {
        border: 15px solid #1e293b;
        padding: 5px;
        background: #f8fafc;
    }

    .cert-content-participation {
        border: 2px solid #1e293b;
        padding: 60px;
        background: white;
        text-align: center;
    }

    .participation-header {
        margin-bottom: 40px;
    }

    .cert-org-participation {
        display: block;
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        letter-spacing: 3px;
        color: #94a3b8;
        font-size: 12px;
        margin-bottom: 20px;
    }

    .cert-title-participation {
        font-size: 56px;
        font-weight: 900;
        margin: 0;
        color: #0f172a;
        letter-spacing: 5px;
    }

    .cert-type-participation {
        display: inline-block;
        font-size: 20px;
        font-weight: 600;
        color: #64748b;
        letter-spacing: 8px;
        margin-top: 5px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 0;
        min-width: 300px;
    }

    .cert-body-participation {
        margin-top: 50px;
    }

    .cert-text-participation {
        font-size: 18px;
        font-style: italic;
        color: #475569;
        margin: 15px 0;
    }

    .cert-name-participation {
        font-size: 42px;
        font-weight: 900;
        color: #1e293b;
        margin: 15px 0;
    }

    .cert-identity-participation {
        font-size: 16px;
        color: #94a3b8;
        font-weight: 700;
    }

    .achievement-box-participation {
        margin-top: 40px;
    }

    .cert-sport-participation {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin: 10px 0;
    }

    .cert-achievement-participation {
        font-size: 14px;
        line-height: 1.6;
        color: #64748b;
        font-style: italic;
        max-width: 500px;
        margin: 20px auto;
    }

    .cert-footer-participation {
        margin-top: 80px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .signature-block-participation {
        width: 180px;
    }

    .sig-line-participation {
        border-top: 1px solid #0f172a;
        margin-bottom: 10px;
    }

    .sig-label-participation {
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
    }

    .cert-date-participation {
        font-weight: 900;
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .cert-stamp-participation {
        width: 100px;
        height: 100px;
        border: 4px double #1e293b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    .stamp-inner-participation {
        font-weight: 900;
        font-size: 10px;
        text-align: center;
    }

    /* Print Styles - Refined for High Fidelity */
    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }

        html,
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            overflow: visible !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .dashboard-header,
        .header-actions,
        .side-column,
        .sidebar,
        .header-main,
        .cert-actions,
        .sidebar-header,
        .sidebar-menu,
        .header,
        .btn-toggle,
        .form-footer,
        .premium-divider,
        #sidebar-toggle,
        .dashboard-container .subtitle-text,
        .dashboard-container .welcome-text {
            display: none !important;
        }

        .main-content,
        .dashboard-container,
        .app-wrapper,
        .content-wrapper {
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            background: transparent !important;
            overflow: visible !important;
        }

        .main-grid,
        .charts-column {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .glass-card {
            display: block !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        #certificate-print-area {
            display: block !important;
            width: 210mm !important;
            /* A4 Width */
            height: 297mm !important;
            /* A4 Height */
            margin: 0 auto !important;
            padding: 0 !important;
            position: relative !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
            page-break-after: always;
            /* If content is too large, we scale it down to fit */
            transform-origin: top center;
            transform: scale(0.95);
            /* Slight scale down to ensure no edge clipping */
        }

        .certificate-paper {
            padding: 20mm !important;
            margin: 0 !important;
            width: 100% !important;
            height: 100% !important;
            box-sizing: border-box !important;
            background: white !important;
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>

<?php include '../includes/footer.php'; ?>