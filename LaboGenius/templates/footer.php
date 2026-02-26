<?php
// templates/footer.php
?>
    </main>
    
    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>LabGenius</h4>
                <p>Plateforme de laboratoire génétique</p>
                <p class="footer-version">Version 1.0.0</p>
            </div>
            <div class="footer-section">
                <h4>Liens rapides</h4>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fas fa-chevron-right"></i> Dashboard</a></li>
                    <li><a href="php/sequenceur.php"><i class="fas fa-chevron-right"></i> Séquenceur</a></li>
                    <li><a href="php/synthese.php"><i class="fas fa-chevron-right"></i> Synthèse</a></li>
                    <li><a href="php/bibliotheque.php"><i class="fas fa-chevron-right"></i> Bibliothèque</a></li>
                    <li><a href="php/carnet.php"><i class="fas fa-chevron-right"></i> Carnet</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Ressources</h4>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Documentation</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Support</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> À propos</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Légal</h4>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Confidentialité</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Conditions</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; <?= date('Y') ?> LabGenius. Tous droits réservés.</p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-github"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
       </main>
    
    <!-- Scripts JavaScript -->
    <?php if (isset($page_js)): ?>
        <script src="<?= $base_url ?>js/<?= $page_js ?>"></script>
    <?php endif; ?>
    
    <!-- Script commun -->
    <script src="<?= $base_url ?>js/main.js"></script>
</body>
</html>