<?php include 'base.php' ?>

<?php startblock('content') ?>

<div class="container" style="margin-bottom: 46%;">
    <div class="row login_box">
        <div class="col-md-12 col-xs-12" align="center">
            <div class="line"><h3 class="current_time">{{ current_time }}</h3></div>
<!--            {% if "book" in user.socialaccount_set.all.0.provider %}-->
<!--            <div class="outter"><img src="//graph.facebook.com/{{ user.socialaccount_set.all.0.uid }}/picture?type=large" class="image-circle"/></div>-->
<!--            {% else %}-->
            <div class="outter"><img src="{{ user.socialaccount_set.all.0.extra_data.picture }}" class="image-circle"/></div>
<!--            {% endif %}-->
            <h1 class="profile_name">{{ user.first_name }} {{ user.last_name }}</h1>
        </div>
        <a href="view_rides.php" class="col-md-6 col-xs-6 follow line" align="center">
            <h3>
                {{ num_rides }} <br/> <span>RIDE(s)</span>
            </h3>
        </a>
        <a href="" class="col-md-6 col-xs-6 follow line" align="center">
            <h3>
                {{ ref_status }} <br/> <span>REF STATUS</span>
            </h3>
        </a>
        <div class="col-md-12 col-xs-12 login_control">
            <div class="control">
                <div class="label">Email Address</div>
                <div class="label_text">{{ user.email }}</div>
            </div>
            <div class="control">
                <div class="label">Username</div>
                <div class="label_text">{{ user.username }}</div>
            </div>

            <div align="center">
<!--                {% if "False" in update_profile %}-->
                <a href="update_profile.php" class="btn btn-orange">Update Profile</a>
<!--                {% endif %}-->
            </div>
        </div>
    </div>
</div>
<?php endblock() ?>