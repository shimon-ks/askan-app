<div id="adModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>יסגר בעוד <span id="countdown">5</span></p>
    <!-- הכנס כאן את השורטקוד של הפרסומת -->
    <?php  the_ad('6448'); ?>
  </div>
</div>


<style>
/* Modal styles */
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  text-align: center;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>