<?php

class diapoView {

public function display($images){ ?>
    <div class="big-cont mx-auto mt-16">
        <div class="container mx-auto">
            <div class="inner-container mx-auto">
                <?php
                if ($images) {
                    foreach ($images as $image) {
                        ?>
                        <img class="bg-contain" src="<?php echo htmlspecialchars($image['lien']); ?>" alt="<?php echo htmlspecialchars($image['titre']); ?>"/>
                        <?php
                    }
                } else {
                    echo "Aucune image trouvée.";
                }
                ?>
            </div>
        </div>
    </div>
<?php }
}
?>

<style>
.big-cont
{
    overflow: hidden;
    
}
.container
{
  overflow: hidden !important;
 position: relative;
 width: 400%;
}
.inner-container
{
    display: flex;
    animation: slide 10s linear infinite;
    position: relative;
    gap: 5%;
}
.inner-container:hover
{
    animation-play-state: paused;
}
img
{
    width: 80%;
    height: 400px;
}
@keyframes slide{
    0%{
        transform: translateX(0);
    }
 100% 
    {
  transform: translateX(-100%); 
    }
 
}
@-webkit-keyframes slide_animation
{
  0%
  {
    left: 0px;
  }
  20%
  {
    left: 1200px;
  }
}

</style>