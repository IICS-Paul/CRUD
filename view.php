<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .img-fluid {
            width: 630px;
            height: 330px;
            border-radius: 10px;
        }

        .info {
            padding: 5px;
            /* margin-top: 3%;
            background-color: rgba(236, 240, 241, 1);
            border-radius: 10px;
            box-shadow: 0px 0px 10px #000; */
        }

        .info p {
            font-weight: bold;
        }

        /* 
        .carousel-control-next,
        .carousel-control-prev {
            filter: invert(100%);
        } */
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php
            include 'database.php';
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT sample.name, sample.age, sample.address, GROUP_CONCAT(images.image) FROM sample INNER JOIN images ON sample.img_ref = images.img_code WHERE sample.id = $id GROUP BY sample.name ORDER BY sample.name ASC";
                $results = $con->query($sql);

                if (!$results) {
                    die('Error executing query: ' . $con->error);
                }

                while ($row = $results->fetch_assoc()) {

                    $name = $row['name'];
                    $age = $row['age'];
                    $address = $row['address'];

                    $images = explode(',', $row["GROUP_CONCAT(images.image)"]);
                    $count = count($images);
                    if ($count == 1) {
                        echo '<img class="img-fluid" src="' . "image/" . $images[0] . '" alt="">';
                    } else if ($count >= 2) {
                        echo '<div id="carouselExampleControls" class="carousel slide carousel-fade" data-bs-ride="carousel">';
                        echo '<div class="carousel-inner">';
                        for ($i = 0; $i < $count; $i++) {
                            echo '<div class="carousel-item';
                            if ($i == 0) {
                                echo ' active';
                            }
                            echo '"data-bs-interval="2830">';
                            echo '<img class="img-fluid" src="' . "image/" . $images[$i] . '" alt="">';
                            echo '</div>';
                        }

                        echo '</div>';
                        echo '<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">';
                        echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                        echo '<span class="visually-hidden">Previous</span>';
                        echo '</button>';
                        echo '<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">';
                        echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                        echo '<span class="visually-hidden">Next</span>';
                        echo '</button>';
                        echo '</div>';
                        echo '</div>';
                    }

                    echo '<div class="info">';
                    echo '<h2>' . $name . '</h2>';
                    echo '<p>Age: ' . $age . '</p>';
                    echo '<p>Address: ' . $address . '</p>';
                    echo '</div>';
                }
            }
            $con->close();

            ?>
        </div>
    </div>
</body>

</html>



<!-- <input id="files" type="file" accept="image/*" multiple>
<div id="preview"></div>

<style>
    #preview img {
        height: 200px;
        width: 250px;
    }

    #preview {
        display: grid;
        grid-template-columns: auto auto auto;
        justify-content: center;
        grid-gap: 20px;
    }
</style>

<script>

    const el = (sel, par) => (par || document).querySelector(sel);
    const elNew = (tag, props) => Object.assign(document.createElement(tag), props);


    // Preview images before upload:

    const elFiles = el("#files");
    const elPreview = el("#preview");

    const previewImage = (props) => elPreview.append(elNew("img", props));

    const reader = (file, method = "readAsDataURL") => new Promise((resolve, reject) => {
        const fr = new FileReader();
        fr.onload = () => resolve({
            file,
            result: fr.result
        });
        fr.onerror = (err) => reject(err);
        fr[method](file);
    });

    const previewImages = async (files) => {
        // Remove existing preview images
        elPreview.innerHTML = "";

        let filesData = [];

        try {
            // Read all files. Return Array of Promises
            const readerPromises = files.map((file) => reader(file));
            filesData = await Promise.all(readerPromises);
        } catch (err) {
            // Notify the user that something went wrong.
            elPreview.textContent = "An error occurred while loading images. Try again.";
            // In this specific case Promise.all() might be preferred over
            // Promise.allSettled(), since it isn't trivial to modify a FileList
            // to a subset of files of what the user initially selected.
            // Therefore, let's just stash the entire operation.
            console.error(err);
            return; // Exit function here.
        }

        // All OK. Preview images:
        filesData.forEach(data => {
            previewImage({
                src: data.result, // Base64 String
                alt: data.file.name // File.name String
            });
        });
    };

    elFiles.addEventListener("change", (ev) => {
        if (!ev.currentTarget.files) return; // Do nothing.
        previewImages([...ev.currentTarget.files]);
    });
</script> -->

<!-- .foo {
display: block;
position: relative;
width: 300px;
margin: auto;
cursor: pointer;
border: 0;
height: 60px;
border-radius: 5px;
outline: 0;
margin-bottom: 1%;

}
.foo:hover:after {
background: #5978f8;
}
.foo:after {
transition: 200ms all ease;
border-bottom: 3px solid rgba(0,0,0,.2);
background: #3c5ff4;
text-shadow: 0 2px 0 rgba(0,0,0,.2);
color: #fff;
font-size: 20px;
text-align: center;
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
display: block;
content: 'Upload Photos';
line-height: 60px;
border-radius: 5px;
} -->